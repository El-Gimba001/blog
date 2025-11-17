<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// routes/api.php (preferred for APIs)
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AuditorController; // Add this import

// ==================== PUBLIC ROUTES ====================

Route::get('/', function () {
    return view('welcome');
});

// ==================== AUTHENTICATED ROUTES ====================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
});

// ==================== SALES & TRANSACTION ROUTES ====================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sales-entry', [TransactionController::class, 'create'])->name('sales.entry');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/daily-transactions', [TransactionController::class, 'dailyTransactions'])->name('daily.transactions');
    Route::get('/stock-out', [TransactionController::class, 'stockOut'])->name('stock.out');
    Route::get('/sold-items', [TransactionController::class, 'soldItems'])->name('sold.items');
    
    // Transaction details
    Route::get('/transaction/{id}/details', [TransactionController::class, 'showDetails'])->name('transactions.details');

    // Daily transactions
    Route::get('/daily-transactions', [TransactionController::class, 'daily'])->name('daily.transactions');
});

// ==================== PRODUCT ROUTES ====================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('/stock-list', [ProductController::class, 'index'])->name('stock.list');
    
    // Restock existing product
    Route::get('/products/restock', [ProductController::class, 'restock'])->name('products.restock');
    Route::post('/products/restock', [ProductController::class, 'updateStock'])->name('products.updateStock');
});

// ==================== USER MANAGEMENT ROUTES ====================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/manage', [UserController::class, 'manage'])->name('users.manage');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/change-password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

// ==================== AUDITOR ROUTES ====================

Route::middleware(['auth', 'verified'])->prefix('auditor')->name('auditor.')->group(function () {
    Route::get('/dashboard', [AuditorController::class, 'dashboard'])->name('dashboard');
    Route::post('/adjust-stock', [AuditorController::class, 'adjustStock'])->name('adjust-stock');
    Route::get('/reports', [AuditorController::class, 'reports'])->name('reports');
    Route::get('/reports/create', [AuditorController::class, 'createReport'])->name('reports.create');
    Route::post('/reports/store', [AuditorController::class, 'storeReport'])->name('reports.store');
});

// ==================== LEDGER & CUSTOMER ROUTES ====================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/ledger/customer-ledger', [ViewController::class, 'customerLedger'])->name('ledger.customer-ledger');
    
    Route::get('/customers/create', function () {
        return view('/ledger/customers.create-customer');
    })->name('customers.create');

    Route::get('/customers/alter', function () {
        return view('/ledger/customers.alter-customer');
    })->name('customers.alter');

    Route::get('/customers/view-all', function () {
        return view('/ledger/customers.view-all-customers');
    })->name('customers.view-all');

    Route::get('/customers/track', function () {
        return view('/ledger/customers.track-transactions');
    })->name('customers.track');
});

// ==================== LOCATION ROUTES ====================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/locations/manage', function () {
        return view('location.manage-location');
    })->name('locations.manage');
});

// Manager Report Routes
Route::get('/manager/reports', [ManagerReportController::class, 'getManagerReports'])->name('manager.reports');
Route::post('/manager/send-report', [ManagerReportController::class, 'sendReport'])->name('manager.send-report');
Route::get('/manager/today-report-data', [ManagerReportController::class, 'getTodayReportData'])->name('manager.today-report-data');

// ==================== API & TEST ROUTES ====================

Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);

Route::get('/api/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is working 🎉'
    ]);
});

require __DIR__.'/auth.php';