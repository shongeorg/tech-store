<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\StoreCartController;
use App\Http\Controllers\StoreCatalogController;
use App\Http\Controllers\StoreCheckoutController;
use App\Http\Controllers\StoreHomeController;
use App\Http\Controllers\StorePageController;
use App\Http\Controllers\StoreProductController;
use App\Http\Controllers\StoreSearchController;
use Illuminate\Support\Facades\Route;

// ===================== Storefront =====================
Route::get('/', StoreHomeController::class)->name('home');

// Starter-kit Inertia dashboard (використовується Vue-частиною старткіта)
Route::inertia('dashboard', 'Dashboard')->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/catalog', [StoreCatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/{category:slug}', [StoreCatalogController::class, 'category'])->name('catalog.category');
Route::get('/product/{product:slug}', StoreProductController::class)->name('product');
Route::get('/search', StoreSearchController::class)->name('search');

Route::get('/cart', [StoreCartController::class, 'index'])->name('cart');
Route::post('/cart/add', [StoreCartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [StoreCartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [StoreCartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/promo', [StoreCartController::class, 'promo'])->name('cart.promo');
Route::post('/cart/promo/clear', [StoreCartController::class, 'promoClear'])->name('cart.promo.clear');

Route::get('/checkout', [StoreCheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [StoreCheckoutController::class, 'store'])->name('checkout.store');
Route::get('/success', [StorePageController::class, 'success'])->name('store.success');
Route::get('/profile', StorePageController::class.'@profile')->name('profile');

// ===================== Admin =====================
Route::middleware(['auth', 'is.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::resource('products', Admin\ProductController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('image-search', [Admin\ProductController::class, 'imageSearch'])
            ->name('products.image-search');

        Route::get('categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::delete('categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('promos', [Admin\PromoCodeController::class, 'index'])->name('promos.index');
        Route::post('promos', [Admin\PromoCodeController::class, 'store'])->name('promos.store');
        Route::post('promos/{promoCode}/toggle', [Admin\PromoCodeController::class, 'toggle'])->name('promos.toggle');
        Route::delete('promos/{promoCode}', [Admin\PromoCodeController::class, 'destroy'])->name('promos.destroy');
    });

// Starter-kit settings routes (Cabinet: profile/security)
require __DIR__.'/settings.php';
