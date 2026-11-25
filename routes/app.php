<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ItemController;


// CUSTOMER //
Route::get('/', function () {
    return redirect()->route('menu');
});
// MENU
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
// CART
Route::get('/cart', [MenuController::class, 'cart'])->name('cart');
Route::post('/cart/add', [MenuController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [MenuController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [MenuController::class, 'removeCart'])->name('cart.remove');
Route::get('/cart/clear', [MenuController::class, 'clearCart'])->name('cart.clear');
// CHECKOUT
Route::get('/checkout', [MenuController::class, 'checkout'])->name('checkout');
Route::post('/checkout/store', [MenuController::class, 'storeOrder'])->name('checkout.store');
Route::post('/checkout/payment/confirm/{orderCode}', [MenuController::class, 'confirmQrPayment'])->name('checkout.payment.confirm');
Route::get('/checkout/success/{orderId}', [MenuController::class, 'checkoutSuccess'])->name('checkout.success');

// ADMIN ROUTES //
Route::middleware('role:admin')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::post('items/update-status/{order}', [ItemController::class, 'updateStatus'])->name('items.updateStatus');
});

Route::middleware('role:admin|cashier|chef')->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
    Route::post('/orders/{order}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});





