<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\ManagerReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditReportController;



// ==================== PUBLIC ROUTES ====================

Route::get('/', function () {
    return view('welcome');
});

// ==================== AUTHENTICATED ROUTES ====================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');
});

// ==================== SALES & TRANSACTION ROUTES ====================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sales-entry', [TransactionController::class, 'create'])->name('sales.entry');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/daily', [TransactionController::class, 'dailyTransactions'])->name('transaction.daily');
    Route::get('/stock-out', [TransactionController::class, 'stockOut'])->name('stock.out');
    Route::get('/sold-items', [TransactionController::class, 'soldItems'])->name('sold.items');
    

    
    // Transaction details
    Route::get('/transaction/{id}/details', [TransactionController::class, 'showDetails'])->name('transactions.details');

});

    // Add this to your routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    // ... your existing routes ...
    
    Route::get('/debts', function () {
        return view('debts.index'); // You'll need to create this view
    })->name('debts');
});

// ==================== PRODUCT ROUTES ====================

Route::middleware(['auth', 'verified'])->group(function () {

    // 🔹 CUSTOM PRODUCT ROUTES FIRST
    Route::get('/products/low-stock', [ProductController::class, 'lowStock'])
        ->name('products.low-stock');

    Route::get('/products/critical-stock', [ProductController::class, 'criticalStock'])
        ->name('products.critical-stock');

    Route::get('/products/out-of-stock', [ProductController::class, 'outOfStock'])
        ->name('products.out-of-stock');

    Route::get('/products/restock', [ProductController::class, 'restock'])
        ->name('products.restock');

    Route::post('/products/restock', [ProductController::class, 'updateStock'])
        ->name('products.updateStock');

    // 🔹 RESOURCE ROUTE LAST
    Route::resource('products', ProductController::class);

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
    
    // API routes for auditor
    Route::get('/overstock-products', [AuditorController::class, 'getOverstockProducts'])->name('overstock-products');
    Route::get('/stats', [AuditorController::class, 'getAuditorStats'])->name('stats');
    Route::get('/product/{product}', [AuditorController::class, 'getProductDetails'])->name('product-details');
    Route::post('/send-report', [AuditorController::class, 'sendAuditReport'])->name('send-report');
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

// ==================== MANAGER REPORT ROUTES ====================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/manager/reports', [ManagerReportController::class, 'getManagerReports'])->name('manager.reports');
    Route::post('/manager/send-report', [ManagerReportController::class, 'sendReport'])->name('manager.send-report');
    Route::get('/manager/today-report-data', [ManagerReportController::class, 'getTodayReportData'])->name('manager.today-report-data');
});

// ==================== API & TEST ROUTES ====================

Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);

Route::get('/api/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is working 🎉'
    ]);
});

// ==================== STORE MANAGEMENT ROUTES ====================

Route::middleware(['auth', 'verified'])->prefix('store')->name('store.')->group(function () {
    // Store Dashboard - Default to user's primary store
    Route::get('/dashboard', [App\Http\Controllers\StoreController::class, 'dashboard'])->name('dashboard');
    
    // Store Dashboard with specific store
    Route::get('/dashboard/{emporium}', [App\Http\Controllers\StoreController::class, 'dashboardWithStore'])->name('dashboard.store');
    
    // Switch current store context
    Route::post('/switch', [App\Http\Controllers\StoreController::class, 'switchStore'])->name('switch');
    
    // Store-specific data
    Route::get('/{emporium}/products', [App\Http\Controllers\StoreController::class, 'products'])->name('products');
    Route::get('/{emporium}/customers', [App\Http\Controllers\StoreController::class, 'customers'])->name('customers');
    Route::get('/{emporium}/transactions', [App\Http\Controllers\StoreController::class, 'transactions'])->name('transactions');
    Route::get('/{emporium}/reports', [App\Http\Controllers\StoreController::class, 'reports'])->name('reports');
});

// ==================== CUSTOMER MANAGEMENT ROUTES ====================

Route::middleware(['auth', 'verified'])->prefix('customers')->name('customers.')->group(function () {
    // Customer CRUD
    Route::get('/', [App\Http\Controllers\CustomerController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CustomerController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CustomerController::class, 'store'])->name('store');
    Route::get('/{customer}', [App\Http\Controllers\CustomerController::class, 'show'])->name('show');
    Route::get('/{customer}/edit', [App\Http\Controllers\CustomerController::class, 'edit'])->name('edit');
    Route::put('/{customer}', [App\Http\Controllers\CustomerController::class, 'update'])->name('update');
    Route::delete('/{customer}', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('destroy');
    
    // Customer Ledger
    Route::get('/{customer}/ledger', [App\Http\Controllers\CustomerController::class, 'ledger'])->name('ledger');
    Route::post('/{customer}/ledger/entry', [App\Http\Controllers\CustomerController::class, 'addLedgerEntry'])->name('ledger.entry');
    
    // Customer Search/Autocomplete
    Route::get('/search', [App\Http\Controllers\CustomerController::class, 'search'])->name('search');
});

// ==================== AUDIT WORKFLOW ROUTES ====================

Route::middleware(['auth', 'verified'])->prefix('audits')->name('audits.')->group(function () {
    // Audit Reports Management
    Route::get('/', [App\Http\Controllers\AuditReportController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\AuditReportController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\AuditReportController::class, 'store'])->name('store');
    Route::get('/{auditReport}', [App\Http\Controllers\AuditReportController::class, 'show'])->name('show');
    
    // Approval Workflow
    Route::post('/{auditReport}/manager-approve', [App\Http\Controllers\AuditReportController::class, 'managerApprove'])->name('manager.approve');
    Route::post('/{auditReport}/manager-reject', [App\Http\Controllers\AuditReportController::class, 'managerReject'])->name('manager.reject');
    Route::post('/{auditReport}/admin-approve', [App\Http\Controllers\AuditReportController::class, 'adminApprove'])->name('admin.approve');
    Route::post('/{auditReport}/admin-reject', [App\Http\Controllers\AuditReportController::class, 'adminReject'])->name('admin.reject');
    
    // Auditor-specific
    Route::get('/my-reports', [App\Http\Controllers\AuditReportController::class, 'myReports'])->name('my.reports');
    Route::get('/pending-approval', [App\Http\Controllers\AuditReportController::class, 'pendingApproval'])->name('pending.approval');
});

// ==================== TRANSACTION HOLD/RELEASE ROUTES ====================

Route::middleware(['auth', 'verified'])->prefix('transactions')->name('transactions.')->group(function () {
    // Hold/Release functionality
    Route::post('/{transaction}/hold', [App\Http\Controllers\TransactionHoldController::class, 'hold'])->name('hold');
    Route::post('/{transaction}/release', [App\Http\Controllers\TransactionHoldController::class, 'release'])->name('release');
    
    // View held transactions
    Route::get('/held', [App\Http\Controllers\TransactionHoldController::class, 'held'])->name('held');
});

// ==================== LOW STOCK ALERTS ROUTES ====================

Route::middleware(['auth', 'verified'])->prefix('alerts')->name('alerts.')->group(function () {
    Route::get('/low-stock', [App\Http\Controllers\AlertController::class, 'lowStock'])->name('low.stock');
    Route::post('/low-stock/mark-read', [App\Http\Controllers\AlertController::class, 'markAsRead'])->name('low.stock.mark.read');
    Route::get('/low-stock/count', [App\Http\Controllers\AlertController::class, 'getAlertCount'])->name('low.stock.count');
});

// ==================== DAILY SALES REPORT ROUTES ====================

Route::middleware(['auth', 'verified'])->prefix('reports')->name('reports.')->group(function () {
    // Daily Sales Reports
    Route::get('/daily-sales', [App\Http\Controllers\DailySalesReportController::class, 'index'])->name('daily.sales');
    Route::get('/daily-sales/{date}', [App\Http\Controllers\DailySalesReportController::class, 'show'])->name('daily.sales.show');
    Route::get('/daily-sales/generate', [App\Http\Controllers\DailySalesReportController::class, 'generate'])->name('daily.sales.generate');
    
    // Profit Reports
    Route::get('/profit', [App\Http\Controllers\ProfitReportController::class, 'index'])->name('profit');
    Route::get('/profit/daily', [App\Http\Controllers\ProfitReportController::class, 'daily'])->name('profit.daily');
    Route::get('/profit/monthly', [App\Http\Controllers\ProfitReportController::class, 'monthly'])->name('profit.monthly');
});

// ==================== AUDIT LOGS ROUTES ====================

Route::middleware(['auth', 'verified'])->prefix('audit-logs')->name('audit.logs.')->group(function () {
    Route::get('/', [App\Http\Controllers\AuditLogController::class, 'index'])->name('index');
    Route::get('/user/{user}', [App\Http\Controllers\AuditLogController::class, 'userLogs'])->name('user');
    Route::get('/store/{emporium}', [App\Http\Controllers\AuditLogController::class, 'storeLogs'])->name('store');
    Route::get('/type/{type}', [App\Http\Controllers\AuditLogController::class, 'typeLogs'])->name('type');
});

// ==================== AUDIT REPORTS (WORKFLOW) ====================

// Auditor submits report
Route::middleware(['auth', 'role:auditor'])->group(function () {
    Route::post('/audit-reports', [AuditReportController::class, 'store'])
        ->name('audit.reports.store');

    Route::get('/audit-reports/mine', [AuditReportController::class, 'myReports'])
        ->name('audit.reports.mine');
});

// Manager approves auditor report

Route::middleware(['auth'])->prefix('manager')->group(function () {
    Route::get('/audit-reports', [AuditReportController::class, 'pending'])
        ->name('manager.audit.reports');

    Route::post('/audit-reports/{id}/approve', [AuditReportController::class, 'approve'])
        ->name('manager.audit.approve');

    Route::post('/audit-reports/{id}/reject', [AuditReportController::class, 'reject'])
        ->name('manager.audit.reject');

    Route::post('/audit-reports/{id}/send-admin', [AuditReportController::class, 'sendToAdmin'])
        ->name('manager.audit.send-admin');

        Route::get('/audit-reports/history', [AuditReportController::class, 'history'])
        ->name('manager.audit.history');
});

// Admin final approval
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/audit-reports/approved-by-manager', [AuditReportController::class, 'pendingAdmin'])
        ->name('audit.reports.admin.pending');

    Route::post('/audit-reports/{report}/final-approve', [AuditReportController::class, 'adminApprove'])
        ->name('audit.reports.admin.approve');

    Route::post('/audit-reports/{report}/final-reject', [AuditReportController::class, 'adminReject'])
        ->name('audit.reports.admin.reject');
});

Route::middleware(['auth'])
    ->get('/manager/audit-reports', [AuditReportController::class, 'pending'])
    ->name('manager.audit.reports');
require __DIR__.'/auth.php';