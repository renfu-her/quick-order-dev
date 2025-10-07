<?php

declare(strict_types=1);

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\MemberAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

// Member Auth Routes
Route::get('/auth', [MemberAuthController::class, 'showAuth'])->name('member.auth');
Route::post('/login', [MemberAuthController::class, 'login'])->name('member.login');
Route::post('/register', [MemberAuthController::class, 'register'])->name('member.register');
Route::post('/logout', [MemberAuthController::class, 'logout'])->name('member.logout');

// Frontend Routes
Route::get('/', [FrontendController::class, 'index'])->name('home');

// Store Routes
Route::get('/stores/{store}', [StoreController::class, 'show'])->name('store.show');
Route::post('/stores/quick-order', [StoreController::class, 'quickOrder'])->name('store.quick-order');

// Product Routes
Route::get('/products/{product}', [ProductController::class, 'show'])->name('product.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('order.confirmation');
