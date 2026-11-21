<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ItemController;


// CUSTOMER
Route::get('/', function () {
    return redirect()->route('menu');
});

Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/cart', [MenuController::class, 'cart'])->name('cart');
Route::post('/cart/add', [MenuController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [MenuController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [MenuController::class, 'removeCart'])->name('cart.remove');
Route::get('/cart/clear', [MenuController::class, 'clearCart'])->name('cart.clear');

Route::get('/checkout', [MenuController::class, 'checkout'])->name('checkout');
Route::post('/checkout/store', [MenuController::class, 'storeOrder'])->name('checkout.store');
Route::post('/checkout/payment/confirm/{orderCode}', [MenuController::class, 'confirmQrPayment'])->name('checkout.payment.confirm');
Route::get('/checkout/success/{orderId}', [MenuController::class, 'checkoutSuccess'])->name('checkout.success');


// ADMIN
Route::get('dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');
Route::resource('categories', CategoryController::class);
Route::resource('items', ItemController::class);
// Accept a regular browser POST as a fallback if the form's PUT override is absent.
Route::post('items/{item}', [ItemController::class, 'update'])->name('items.update.post');
Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::patch('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status.update');
Route::resource('orders', OrderController::class);



