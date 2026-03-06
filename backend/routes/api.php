<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SupplierController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

// Public product routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Public category routes
Route::get('/categories', [CategoryController::class, 'index']);

// Public supplier routes
Route::get('/suppliers', [SupplierController::class, 'index']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // User profile
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
    
    // Orders
    Route::apiResource('orders', OrderController::class);
    
    // Admin-only routes
    Route::middleware('admin')->group(function () {
        // Dashboard statistics
        Route::get('/dashboard/statistics', [DashboardController::class, 'statistics']);
        Route::get('/dashboard/recent-orders', [DashboardController::class, 'recentOrders']);
        
        // Admin orders management (all orders)
        Route::get('/admin/orders', [OrderController::class, 'adminIndex']);
        
        // Product and category management
        Route::apiResource('products', ProductController::class)->except(['index', 'show']);
        Route::apiResource('categories', CategoryController::class)->except(['index']);
        Route::apiResource('suppliers', SupplierController::class)->except(['index']);

        // System information (environment, production mode flag)
        Route::get('/system/info', function () {
            return response()->json([
                'environment'  => app()->environment(),
                'is_production' => app()->environment('production'),
            ]);
        });
    });
});
