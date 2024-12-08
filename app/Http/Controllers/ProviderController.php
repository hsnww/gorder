<?php

namespace App\Http\Controllers;

use App\Models\Provider;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::all(); // جلب جميع المزودين
        return view('providers.index', compact('providers'));
    }
    public function show(Provider $provider)
    {
        $products = $provider->products()->with('category')->get(); // جلب المنتجات المرتبطة بالمزود
        return view('providers.show', compact('provider', 'products'));
    }

}

