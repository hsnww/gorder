<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::all(); // جلب جميع التصنيفات
        return view('categories.index', compact('categories'));
    }
    public function show(ProductCategory $category)
    {
        $products = $category->products()->get(); // جلب المنتجات المرتبطة بهذا التصنيف
        return view('categories.show', compact('category', 'products'));
    }
}
