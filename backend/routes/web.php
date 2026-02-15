<?php

use Illuminate\Support\Facades\Route;

// Frontend is handled by Vite dev server on port 5173
// All frontend routes are disabled - use API routes instead
Route::get('/', function () {
    return response()->json([
        'message' => 'DealMindanao API',
        'version' => '1.0.0',
        'frontend_url' => env('FRONTEND_URL', 'http://localhost:5173'),
        'api_url' => env('APP_URL', 'http://localhost:8000') . '/api',
        'documentation' => env('APP_URL', 'http://localhost:8000') . '/api/docs',
    ]);
});

// Web routes commented out - frontend is separate
/*
Route::get('/', [HomeController::class, 'index']);
Route::get('/shop', [ShopController::class, 'index']);
Route::get('/shop/search', [ShopController::class, 'search']);
Route::get('/shop/{slug}', [ShopController::class, 'show']);

Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add/{product}', [CartController::class, 'add']);
Route::post('/cart/update/{product}', [CartController::class, 'update']);
Route::post('/cart/remove/{product}', [CartController::class, 'remove']);

Route::get('/checkout', [CheckoutController::class, 'index']);
Route::post('/checkout', [CheckoutController::class, 'store']);
Route::view('/checkout/success', 'checkout-success');

Route::get('/about', [PageController::class, 'show'])->defaults('slug', 'about');
Route::get('/contact', [PageController::class, 'show'])->defaults('slug', 'contact');
Route::get('/terms', [PageController::class, 'show'])->defaults('slug', 'terms');
Route::get('/copyrights', [PageController::class, 'show'])->defaults('slug', 'copyrights');
Route::get('/pages/{slug}', [PageController::class, 'show']);

Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('admin.login');
Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::view('/', 'admin.home');
    Route::get('/products', [AdminProductController::class, 'index']);
    Route::get('/products/search', [AdminProductController::class, 'search']);
    Route::get('/products/create', [AdminProductController::class, 'create']);
    Route::post('/products', [AdminProductController::class, 'store']);
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit']);
    Route::put('/products/{product}', [AdminProductController::class, 'update']);
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy']);
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{order}', [AdminOrderController::class, 'show']);
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus']);
    Route::get('/companies', [AdminCompanyController::class, 'index']);
    Route::get('/companies/create', [AdminCompanyController::class, 'create']);
    Route::post('/companies', [AdminCompanyController::class, 'store']);
    Route::get('/companies/{company}/edit', [AdminCompanyController::class, 'edit']);
    Route::put('/companies/{company}', [AdminCompanyController::class, 'update']);
    Route::delete('/companies/{company}', [AdminCompanyController::class, 'destroy']);
    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::get('/categories/create', [AdminCategoryController::class, 'create']);
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit']);
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update']);
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy']);
    Route::get('/pages', [AdminPageController::class, 'index']);
    Route::get('/pages/create', [AdminPageController::class, 'create']);
    Route::post('/pages', [AdminPageController::class, 'store']);
    Route::get('/pages/{page}/edit', [AdminPageController::class, 'edit']);
    Route::put('/pages/{page}', [AdminPageController::class, 'update']);
    Route::delete('/pages/{page}', [AdminPageController::class, 'destroy']);
    Route::get('/navigation', [AdminNavigationController::class, 'index']);
    Route::get('/navigation/create', [AdminNavigationController::class, 'create']);
    Route::post('/navigation', [AdminNavigationController::class, 'store']);
    Route::get('/navigation/{navigation}/edit', [AdminNavigationController::class, 'edit']);
    Route::put('/navigation/{navigation}', [AdminNavigationController::class, 'update']);
    Route::delete('/navigation/{navigation}', [AdminNavigationController::class, 'destroy']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/

// Auth routes are required for Sanctum authentication
require __DIR__.'/auth.php';
