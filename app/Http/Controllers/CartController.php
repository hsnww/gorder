<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
// عرض جميع السلال
    public function index()
    {
        $cart = session()->get('cart', []); // السلة الأولية
        $providerCarts = session()->get('provider_carts', []); // سلال المزودين

        // تنظيف السلة الأولية من العناصر غير الصحيحة
        $cart = array_filter($cart, function ($item) {
            return isset($item['product_id']) && isset($item['quantity']);
        });

        // تحديث السلة الأولية النظيفة في الجلسة
        session()->put('cart', $cart);

        return view('cart.all_carts', compact('cart', 'providerCarts'));
    }
    // إضافة صنف إلى السلة
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // التحقق من وجود المنتج في المخازن
        $productWarehouse = ProductWarehouse::where('product_id', $productId)->first();

        if (!$productWarehouse) {
            return redirect()->back()->with('error', 'Product not available in any warehouse.');
        }

        // التحقق إذا كان المنتج موجودًا مسبقًا في السلة
        $existingIndex = null;
        foreach ($cart as $index => $item) {
            if ($item['product_id'] == $productId) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            // تحديث الكمية إذا كان المنتج موجودًا
            $cart[$existingIndex]['quantity'] += $quantity;
        } else {
            // إضافة المنتج كعنصر جديد إذا لم يكن موجودًا
            $cart[] = [
                'product_id' => $productId,
                'name' => $productWarehouse->product->name_en, // أو أي اسم مناسب
                'photo' => $productWarehouse->product->photo,
                'quantity' => $quantity,
                'provider_id' => $productWarehouse->provider_id,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $quantity;
                break;
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Quantity updated successfully.');
    }
    public function calculate()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // الخطوة 1: البحث عن تاجر واحد يوفر جميع السلع
        $providers = DB::table('providers')->pluck('id');
        $bestProvider = null;
        $bestPrice = PHP_INT_MAX;

        foreach ($providers as $providerId) {
            $totalPrice = 0;
            $allAvailable = true;

            foreach ($cart as $item) {
                $productWarehouse = DB::table('product_warehouses')
                    ->where('product_id', $item['product_id'])
                    ->where('provider_id', $providerId)
                    ->where('quantity', '>=', $item['quantity'])
                    ->first();

                if (!$productWarehouse) {
                    $allAvailable = false;
                    break;
                }

                // احتساب التكلفة الإجمالية لهذه السلعة بناءً على الكمية
                $itemTotal = $productWarehouse->price * $item['quantity'];
                $totalPrice += $itemTotal;
            }

            // مقارنة الأسعار الإجمالية مع الأخذ في الاعتبار الكمية
            if ($allAvailable && $totalPrice < $bestPrice) {
                $bestPrice = $totalPrice;
                $bestProvider = $providerId;
            }
        }

        // إذا تم العثور على تاجر واحد يوفر جميع السلع
        if ($bestProvider) {
            $providerDetails = DB::table('providers')->where('id', $bestProvider)->first();

            return view('cart.result_single_provider', [
                'bestPrice' => $bestPrice,
                'provider' => $providerDetails,
                'cart' => $cart,
            ]);
        }

        // الخطوة 2: إذا لم يتم العثور على تاجر واحد يوفر جميع السلع
        return redirect()->route('cart.splitOption')->with('info', 'No single provider can fulfill your order. Try splitting your cart.');
    }
    public function splitOption()
    {
        return view('cart.split_option');
    }
    public function splitCalculate()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $remainingCart = $cart;
        $fulfilledOrders = [];
        $unfulfilledItems = [];

        while (!empty($remainingCart)) {
            $providers = DB::table('providers')->pluck('id');
            $bestProvider = null;
            $bestPrice = PHP_INT_MAX;
            $fulfilledItems = [];
            $fulfilledItemsCount = 0;

            foreach ($providers as $providerId) {
                $totalPrice = 0;
                $currentFulfilledItems = [];
                $currentFulfilledCount = 0;

                foreach ($remainingCart as $item) {
                    $productWarehouse = DB::table('product_warehouses')
                        ->where('product_id', $item['product_id'])
                        ->where('provider_id', $providerId)
                        ->where('quantity', '>=', $item['quantity'])
                        ->first();

                    if ($productWarehouse) {
                        $currentFulfilledItems[] = $item['product_id'];
                        $currentFulfilledCount++;
                        $totalPrice += $productWarehouse->price * $item['quantity'];
                    }
                }

                // اختيار المزود الذي يوفر أكبر عدد من السلع بأقل تكلفة
                if ($currentFulfilledCount > $fulfilledItemsCount ||
                    ($currentFulfilledCount == $fulfilledItemsCount && $totalPrice < $bestPrice)) {
                    $fulfilledItemsCount = $currentFulfilledCount;
                    $bestProvider = $providerId;
                    $bestPrice = $totalPrice;
                    $fulfilledItems = $currentFulfilledItems;
                }
            }

            if ($bestProvider) {
                $fulfilledOrders[] = [
                    'provider_id' => $bestProvider,
                    'items' => $fulfilledItems,
                    'total_price' => $bestPrice,
                ];

                $remainingCart = array_filter($remainingCart, function ($item) use ($fulfilledItems) {
                    return !in_array($item['product_id'], $fulfilledItems);
                });
            } else {
                foreach ($remainingCart as $item) {
                    $unfulfilledItems[] = $item['product_id'];
                }
                break;
            }
        }

        foreach ($fulfilledOrders as &$order) {
            $order['provider'] = DB::table('providers')->where('id', $order['provider_id'])->first();
        }
        session()->put('fulfilled_orders', $fulfilledOrders);

        return view('cart.split_result', [
            'fulfilledOrders' => $fulfilledOrders,
            'unfulfilledItems' => $unfulfilledItems,
        ]);
    }
    public function changeProvider(Request $request)
    {
        Log::info('Request received:', $request->all()); // تسجل البيانات الواردة

        $providerId = $request->input('provider_id');
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // تحقق من صحة المدخلات
        if (!$providerId || !$productId || !$quantity) {
            Log::error('Invalid data provided.', $request->all());
            return response()->json(['success' => false, 'message' => 'Invalid data provided.'], 400);
        }

        // تحديث المزود في الجلسة
        $cart = session()->get('cart', []);
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                $item['provider_id'] = $providerId; // تحديث المزود
            }
        }

        session()->put('cart', $cart);

        Log::info('Cart updated successfully:', session('cart')); // تسجل الجلسة بعد التحديث

        return response()->json(['success' => true, 'message' => 'Provider changed successfully']);
    }
    public function remove(Request $request)
    {
        $productId = $request->product_id;

        // جلب السلة من الجلسة
        $cart = session()->get('cart', []);

        // إزالة المنتج من السلة
        $cart = array_filter($cart, function ($item) use ($productId) {
            return $item['product_id'] != $productId;
        });

        // تحديث السلة في الجلسة
        session()->put('cart', $cart);

//        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
        return redirect()->back()->with('success', 'Product removed from cart!');
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
    public function showInitialCart()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }
    public function viewProviderCart($providerId)
    {
        $providerCarts = session()->get('provider_carts', []);
        if (!isset($providerCarts[$providerId])) {
            return redirect()->route('cart.index')->with('error', __('labels.Cart not found for this provider.'));
        }
        $providerCart = $providerCarts[$providerId];
        return view('cart.provider', compact('providerCart', 'providerId'));
    }
    public function saveSuggestedCarts()
    {
        $fulfilledOrders = session()->get('fulfilled_orders', []);

        if (empty($fulfilledOrders)) {
            return redirect()->back()->with('error', __('labels.No suggested carts to save.'));
        }

        $providerCarts = session()->get('provider_carts', []);

        foreach ($fulfilledOrders as $order) {
            $providerId = $order['provider']->id;

            if (!isset($providerCarts[$providerId])) {
                $providerCarts[$providerId] = ['products' => []];
            }

            foreach ($order['items'] as $productId) {
                // جلب الكمية من السلة الأولية
                $quantity = collect(session('cart'))->firstWhere('product_id', $productId)['quantity'] ?? 1;

                // التحقق إذا كان المنتج موجودًا في سلة المزود بالفعل
                $existingIndex = array_search($productId, array_column($providerCarts[$providerId]['products'], 'product_id'));

                if ($existingIndex !== false) {
                    // تحديث الكمية إذا كان المنتج موجودًا
                    $providerCarts[$providerId]['products'][$existingIndex]['quantity'] += $quantity;
                } else {
                    // إضافة منتج جديد
                    $product = \App\Models\Product::find($productId);
                    $providerCarts[$providerId]['products'][] = [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'name' => $product ? (app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en) : '',
                        'photo' => $product ? $product->photo : '',
                    ];
                }
            }
        }

        // تحديث سلال المزودين في الجلسة
        session()->put('provider_carts', $providerCarts);

        // تفريغ السلة الأولية والسلال المقترحة
        session()->forget('cart');
        session()->forget('fulfilled_orders');

        return redirect()->route('cart.index')->with('success', __('labels.Suggested carts saved successfully.'));
    }
    public function saveSingleCart(Request $request)
    {
        $providerId = $request->input('provider_id');
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', __('labels.No products in cart to save.'));
        }

        $providerCarts = session()->get('provider_carts', []);

        // التأكد من وجود سلة للمزود أو إنشاء واحدة جديدة
        if (!isset($providerCarts[$providerId])) {
            $providerCarts[$providerId] = ['products' => []];
        }

        // إضافة المنتجات إلى سلة المزود
        foreach ($cart as $item) {
            if ($item['provider_id'] == $providerId) {
                // التحقق إذا كان المنتج موجودًا مسبقًا
                $existingIndex = array_search($item['product_id'], array_column($providerCarts[$providerId]['products'], 'product_id'));

                if ($existingIndex !== false) {
                    // تحديث الكمية إذا كان المنتج موجودًا
                    $providerCarts[$providerId]['products'][$existingIndex]['quantity'] += $item['quantity'];
                } else {
                    // إضافة المنتج كعنصر جديد
                    $providerCarts[$providerId]['products'][] = [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'name' => $item['name'],
                        'photo' => $item['photo'],
                    ];
                }
            }
        }

        session()->put('provider_carts', $providerCarts);
        session()->forget('cart'); // تفريغ السلة الأولية

        return redirect()->route('cart.index')->with('success', __('labels.Suggested cart saved successfully.'));
    }


}
