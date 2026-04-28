<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/shop', [StorefrontController::class, 'shop'])->name('shop.index');
Route::get('/products/{product:slug}', [StorefrontController::class, 'show'])->name('products.show');
Route::get('/cart', [StorefrontController::class, 'cart'])->name('cart.index');
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/tra-cuu-don-hang', [OrderController::class, 'lookup'])->name('orders.lookup');
Route::post('/tra-cuu-don-hang', [OrderController::class, 'search'])->name('orders.lookup.search');
Route::get('/don-hang/{order}/hoan-tat', [OrderController::class, 'success'])->name('orders.success');

Route::post('/cart/items/{product:slug}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/items/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{product:slug}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::redirect('/admin', '/admin/products');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/tai-khoan/don-hang', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/tai-khoan/don-hang/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::resource('products', AdminProductController::class)
        ->only(['index', 'store', 'edit', 'update', 'destroy']);
});
