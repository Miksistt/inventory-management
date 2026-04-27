<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncomingInvoiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ReportController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('incoming')->name('incoming.')->middleware('can:manage-inventory')->group(function () {
        Route::resource('invoices', IncomingInvoiceController::class);
        Route::post('invoices/{invoice}/post', [IncomingInvoiceController::class, 'post'])
            ->name('invoices.post');
        Route::post('invoices/{invoice}/cancel', [IncomingInvoiceController::class, 'cancel'])
            ->name('invoices.cancel');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/history', [HistoryController::class, 'index'])
        ->middleware('can:view-reports')
        ->name('history.index');

    Route::prefix('reports')->name('reports.')->middleware('can:view-reports')->group(function () {
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('/incoming', [ReportController::class, 'incoming'])->name('incoming');
        Route::get('/outgoing', [ReportController::class, 'outgoing'])->name('outgoing');
        Route::get('/suppliers', [ReportController::class, 'suppliers'])->name('suppliers');
    });

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
