<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // جلب 8 منتجات من قاعدة البيانات
        $products = Product::latest()->paginate(8);

        // تمرير المنتجات إلى واجهة العرض
        return view('home', compact('products'));
    }
}
