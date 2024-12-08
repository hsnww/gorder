<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|string|max:255',
            'barcode' => 'required|string|unique:products,barcode',
        ]);

        Product::create($request->all());
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|string|max:255',
            'barcode' => 'required|string|unique:products,barcode,' . $product->id,
        ]);

        $product->update($request->all());
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    public function compare($productId)
    {
        // جلب المنتج الأساسي
        $product = Product::findOrFail($productId);

        // جلب الأسعار من المتاجر المختلفة
        $productWarehouses = DB::table('product_warehouses')
            ->join('providers', 'product_warehouses.provider_id', '=', 'providers.id')
            ->where('product_warehouses.product_id', $productId)
            ->orderBy('product_warehouses.price', 'asc')
            ->take(10) // تحديد 10 نتائج فقط
            ->select('product_warehouses.*', 'providers.name as provider_name', 'providers.email as provider_email')
            ->get();

        // عرض صفحة المقارنة
        return view('products.compare', [
            'product' => $product,
            'productWarehouses' => $productWarehouses,
        ]);
    }

}
