<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role->name;
        if ($role === 'kasir') {
            return redirect()->route('pos');
        } elseif ($role === 'gudang') {
            return redirect()->route('purchase-orders.index');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard - Admin & Manager only
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/reports', [ReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
    });

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups/create', [BackupController::class, 'createBackup'])->name('backups.create');
        Route::get('/backups/download/{id}', [BackupController::class, 'downloadBackup'])->name('backups.download');
    });

    // Kasir routes
    Route::middleware('role:kasir,admin')->group(function () {
        Route::get('/pos', [SalesController::class, 'posPage'])->name('pos');
    });

    // Gudang routes
    Route::middleware('role:gudang,admin')->group(function () {
        Route::resource('purchase-orders', PurchaseOrderController::class);
        Route::post('/purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');
        Route::post('/stock/adjust', [StockController::class, 'processAdjust'])->name('stock.adjust.process');
    });
});

require __DIR__.'/auth.php';

