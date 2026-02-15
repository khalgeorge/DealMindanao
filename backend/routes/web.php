<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\AccountController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Web\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Web\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Web\Admin\CompanyController as AdminCompanyController;

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/products/{slug}', [ShopController::class, 'show'])->name('product.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');

// Checkout (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// User Account (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
});

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/partner', [PageController::class, 'partner'])->name('partner');

// Auth Routes (Laravel Breeze provides these)
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::post('/products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggleFeatured');
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');
    
    // Companies
    Route::get('/companies', [AdminCompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [AdminCompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [AdminCompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}/edit', [AdminCompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{company}', [AdminCompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{company}', [AdminCompanyController::class, 'destroy'])->name('companies.destroy');
    Route::post('/companies/{company}/toggle-status', [AdminCompanyController::class, 'toggleStatus'])->name('companies.toggleStatus');
});

// Admin Login (separate from user login)
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login')->middleware('guest');

Route::post('/admin/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (auth()->attempt($credentials)) {
        $request->session()->regenerate();
        
        // Check if user is admin
        if (!auth()->user()->is_admin) {
            auth()->logout();
            return back()->withErrors([
                'email' => 'Access denied. Admin privileges required.',
            ]);
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('admin.login.post')->middleware('guest');

Route::match(['get', 'post'], '/admin/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('admin.login');
})->name('admin.logout');

