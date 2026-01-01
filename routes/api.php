<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Add these routes to your api.php
Route::middleware(['auth:sanctum'])->group(function () {
    // Low stock APIs
    Route::get('/products/low-stock-summary', [ProductController::class, 'lowStockSummary']);
    Route::get('/products/low-stock-count', [ProductController::class, 'lowStockCount']);
    Route::get('/products/low-stock-list', [ProductController::class, 'lowStockList']);
    Route::get('/products/{product}/stock-status', [ProductController::class, 'checkStockStatus']);
});