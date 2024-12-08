<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\CategoryController;


Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', ProductCategoryController::class);
    Route::resource('products', ProductController::class);
});

Route::get('products/{id}', [CartController::class, 'show'])->name('products.show');
Route::get('/cart/calculate', [CartController::class, 'calculate'])->name('cart.calculate');
Route::get('/cart/split-option', [CartController::class, 'splitOption'])->name('cart.splitOption');
Route::get('/cart/split-calculate', [CartController::class, 'splitCalculate'])->name('cart.splitCalculate');
Route::get('/product/compare/{product_id}', [ProductController::class, 'compare'])->name('product.compare');
Route::post('/cart/change-provider', [CartController::class, 'changeProvider'])->name('cart.changeProvider');


Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index'); // عرض السلة
    Route::post('add', [CartController::class, 'addToCart'])->name('cart.add'); // إضافة إلى السلة
    Route::post('remove', [CartController::class, 'remove'])->name('cart.remove'); // إزالة من السلة
    Route::post('checkout', [CartController::class, 'checkout'])->name('cart.checkout'); // الدفع
});

Route::get('/providers', [ProviderController::class, 'index'])->name('providers.index');
Route::get('/providers/{provider}', [ProviderController::class, 'show'])->name('providers.show');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

require __DIR__.'/auth.php';
