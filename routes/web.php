<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OutgoingInvoiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');

    Route::prefix('outgoing')->name('outgoing.')->middleware('can:manage-inventory')->group(function () {
        Route::resource('invoices', OutgoingInvoiceController::class);
        Route::post('invoices/{invoice}/post', [OutgoingInvoiceController::class, 'post'])
            ->name('invoices.post');
        Route::post('invoices/{invoice}/cancel', [OutgoingInvoiceController::class, 'cancel'])
            ->name('invoices.cancel');
    });
});

Route::middleware(['auth', 'can:access-admin-panel'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', AdminProductController::class);
    Route::resource('users', AdminUserController::class);
});

require __DIR__.'/auth.php';
