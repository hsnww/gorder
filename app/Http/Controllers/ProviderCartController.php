<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Product;
use Illuminate\Http\Request;

class ProviderCartController extends Controller
{
    // إضافة منتج إلى سلة المزود
    public function addToCart(Request $request)
    {
        $providerId = $request->input('provider_id');
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        if (!$providerId || !$productId || $quantity <= 0) {
            return redirect()->back()->with('error', 'Invalid provider, product, or quantity.');
        }

        $product = Product::find($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // جلب السلال من الجلسة
        $providerCarts = session()->get('provider_carts', []);

        if (!isset($providerCarts[$providerId])) {
            // إنشاء سلة جديدة إذا لم تكن موجودة
            $providerCarts[$providerId] = ['products' => []];
        }

        // التحقق إذا كان المنتج موجودًا بالفعل في السلة
        $existingIndex = array_search($productId, array_column($providerCarts[$providerId]['products'], 'product_id'));

        if ($existingIndex !== false) {
            // تحديث الكمية إذا كان المنتج موجودًا
            $providerCarts[$providerId]['products'][$existingIndex]['quantity'] += $quantity;
        } else {
            // إضافة منتج جديد مع الحقول الإضافية
            $providerCarts[$providerId]['products'][] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'name' => app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en,
                'photo' => $product->photo,
            ];
        }

        session()->put('provider_carts', $providerCarts);

        return redirect()->route('cart.provider', $providerId)->with('success', 'Product added to cart.');
    }

    // عرض السلة للمزود
    public function viewCart($providerId)
    {

        $cartKey = "provider_cart_{$providerId}";
        $cart = session()->get($cartKey, []);

        $products = collect($cart)->map(function ($item) {
            $product = \App\Models\Product::find($item['product_id']);
            if ($product) {
                return [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                ];
            }
            return null;
        })->filter();

        $provider = \App\Models\Provider::find($providerId);

        return view('providers.cart', [
            'providerId' => $providerId,
            'provider' => $provider,
            'products' => $products,
        ]);
    }


// إزالة منتج من سلة المزود
    public function removeFromCart(Request $request)
    {
        $providerId = $request->input('provider_id');
        $productId = $request->input('product_id');

        // جلب السلال الحالية
        $providerCarts = session()->get('provider_carts', []);

        if (isset($providerCarts[$providerId]) && isset($providerCarts[$providerId]['products'])) {
            // تحديث قائمة المنتجات بإزالة المنتج المحدد
            $providerCarts[$providerId]['products'] = array_filter($providerCarts[$providerId]['products'], function ($product) use ($productId) {
                return $product['product_id'] != $productId;
            });

            // إذا أصبحت السلة فارغة، إزالة السلة بالكامل
            if (empty($providerCarts[$providerId]['products'])) {
                unset($providerCarts[$providerId]);
            }

            // تحديث السلال في الجلسة
            session()->put('provider_carts', $providerCarts);
        }

        return back()->with('success', __('labels.Product removed from cart.'));
    }


}
