<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required|unique:product_categories,name_en',
            'name_ar' => 'required|unique:product_categories,name_ar',
        ]);

        ProductCategory::create($request->only('name_en', 'name_ar'));
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    public function edit(ProductCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, ProductCategory $category)
    {
        $request->validate([
            'name_en' => 'required|unique:product_categories,name_en,' . $category->id,
            'name_ar' => 'required|unique:product_categories,name_ar,' . $category->id,
        ]);

        $category->update($request->only('name_en', 'name_ar'));
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy(ProductCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
