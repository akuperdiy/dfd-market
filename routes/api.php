<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:web')->group(function () {
    Route::get('/products', [ProductController::class, 'apiSearchByBarcode'])->name('api.products.search');
    Route::post('/sales', [SalesController::class, 'store'])->name('api.sales.store');
});

