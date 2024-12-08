<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // عرض السلة
    public function index()
    {
        $cart = session()->get('cart', []);

        // تنظيف السلة من العناصر غير الصحيحة
        $cart = array_filter($cart, function ($item) {
            return isset($item['product_id']) && isset($item['quantity']);
        });

        // تحديث السلة النظيفة في الجلسة
        session()->put('cart', $cart);

        return view('cart.index', compact('cart'));
    }
    // إضافة صنف إلى السلة
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $productWarehouse = ProductWarehouse::where('product_id', $productId)->first();

        if (!$productWarehouse) {
            return redirect()->back()->with('error', 'Product not available in any warehouse.');
        }

        $cart[] = [
            'product_id' => $productId,
            'name' => $productWarehouse->product->name_en, // أو أي اسم مناسب
            'photo' => $productWarehouse->product->photo,
            'quantity' => $quantity,
            'provider_id' => $productWarehouse->provider_id, // تأكد من إضافة هذا المفتاح
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
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

                $totalPrice += $productWarehouse->price * $item['quantity'];
            }

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

        // الخطوة 2: إذا لم يتم العثور على تاجر واحد
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

            foreach ($providers as $providerId) {
                $totalPrice = 0;
                $allAvailable = false;
                $currentFulfilledItems = [];

                foreach ($remainingCart as $item) {
                    $productWarehouse = DB::table('product_warehouses')
                        ->where('product_id', $item['product_id'])
                        ->where('provider_id', $providerId)
                        ->where('quantity', '>=', $item['quantity'])
                        ->first();

                    if ($productWarehouse) {
                        $allAvailable = true;
                        $currentFulfilledItems[] = $item['product_id'];
                        $totalPrice += $productWarehouse->price * $item['quantity'];
                    }
                }

                if ($allAvailable && $totalPrice < $bestPrice) {
                    $bestPrice = $totalPrice;
                    $bestProvider = $providerId;
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
}
