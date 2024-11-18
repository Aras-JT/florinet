<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::redirect('/', '/products');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/fetch', [ProductController::class, 'fetchProducts'])->name('products.fetch');
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
