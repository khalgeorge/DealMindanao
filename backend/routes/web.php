<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\Web\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Web\Admin\HomePageController as AdminHomePageController;
use App\Http\Controllers\Web\Admin\AboutPageController as AdminAboutPageController;
use App\Http\Controllers\Web\Admin\PartnerPageController as AdminPartnerPageController;
use App\Http\Controllers\Web\Admin\ContactPageController as AdminContactPageController;
use App\Http\Controllers\Web\Admin\HelpPageController as AdminHelpPageController;
use App\Http\Controllers\Web\Admin\TrustSafetyPageController as AdminTrustSafetyPageController;
use App\Http\Controllers\Web\Admin\PrivacyPageController as AdminPrivacyPageController;
use App\Http\Controllers\Web\Admin\RefundPolicyPageController as AdminRefundPolicyPageController;
use App\Http\Controllers\Web\Admin\TermsPageController as AdminTermsPageController;
use App\Http\Controllers\Web\Admin\NavigationController as AdminNavigationController;

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('product.show');
Route::get('/products/{slug}', fn($slug) => redirect()->route('product.show', $slug), 301);

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
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/help', [PageController::class, 'help'])->name('help');
Route::get('/refunds', [PageController::class, 'refunds'])->name('refunds');
Route::get('/trust-safety', [PageController::class, 'trustSafety'])->name('trust-safety');

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
    Route::post('/products/upload-image', [AdminProductController::class, 'uploadImage'])->name('products.uploadImage');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::post('/products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggleFeatured');
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/json', [AdminOrderController::class, 'showJson'])->name('orders.showJson');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::match(['post', 'patch'], '/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
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

    // About Page Editor
    Route::get('/about-page', [AdminAboutPageController::class, 'index'])->name('about_page.index');
    Route::post('/about-page', [AdminAboutPageController::class, 'update'])->name('about_page.update');

    // Partner Page Editor
    Route::get('/partner-page', [AdminPartnerPageController::class, 'index'])->name('partner_page.index');
    Route::post('/partner-page', [AdminPartnerPageController::class, 'update'])->name('partner_page.update');

    // Contact Page Editor
    Route::get('/contact-page', [AdminContactPageController::class, 'index'])->name('contact_page.index');
    Route::post('/contact-page', [AdminContactPageController::class, 'update'])->name('contact_page.update');

    // Help Page Editor
    Route::get('/help-page', [AdminHelpPageController::class, 'index'])->name('help_page.index');
    Route::post('/help-page', [AdminHelpPageController::class, 'update'])->name('help_page.update');
    Route::post('/help-page/faqs/reorder', [AdminHelpPageController::class, 'reorderFaqs'])->name('help_page.faqs.reorder');
    Route::post('/help-page/faqs', [AdminHelpPageController::class, 'storeFaq'])->name('help_page.faqs.store');
    Route::put('/help-page/faqs/{faq}', [AdminHelpPageController::class, 'updateFaq'])->name('help_page.faqs.update');
    Route::delete('/help-page/faqs/{faq}', [AdminHelpPageController::class, 'destroyFaq'])->name('help_page.faqs.destroy');
    Route::post('/help-page/faqs/{faq}/toggle', [AdminHelpPageController::class, 'toggleFaq'])->name('help_page.faqs.toggle');

    // Trust & Safety Page Editor
    Route::get('/trust-safety-page', [AdminTrustSafetyPageController::class, 'index'])->name('trust_safety.index');
    Route::post('/trust-safety-page', [AdminTrustSafetyPageController::class, 'update'])->name('trust_safety.update');
    Route::post('/trust-safety-page/items/reorder', [AdminTrustSafetyPageController::class, 'reorderItems'])->name('trust_safety.items.reorder');
    Route::post('/trust-safety-page/items', [AdminTrustSafetyPageController::class, 'storeItem'])->name('trust_safety.items.store');
    Route::put('/trust-safety-page/items/{item}', [AdminTrustSafetyPageController::class, 'updateItem'])->name('trust_safety.items.update');
    Route::delete('/trust-safety-page/items/{item}', [AdminTrustSafetyPageController::class, 'destroyItem'])->name('trust_safety.items.destroy');
    Route::post('/trust-safety-page/items/{item}/toggle', [AdminTrustSafetyPageController::class, 'toggleItem'])->name('trust_safety.items.toggle');

    // Privacy Policy Page Editor
    Route::get('/privacy-page', [AdminPrivacyPageController::class, 'index'])->name('privacy_page.index');
    Route::post('/privacy-page', [AdminPrivacyPageController::class, 'update'])->name('privacy_page.update');
    Route::post('/privacy-page/sections/reorder', [AdminPrivacyPageController::class, 'reorderSections'])->name('privacy_page.sections.reorder');
    Route::post('/privacy-page/sections', [AdminPrivacyPageController::class, 'storeSection'])->name('privacy_page.sections.store');
    Route::put('/privacy-page/sections/{section}', [AdminPrivacyPageController::class, 'updateSection'])->name('privacy_page.sections.update');
    Route::delete('/privacy-page/sections/{section}', [AdminPrivacyPageController::class, 'destroySection'])->name('privacy_page.sections.destroy');
    Route::post('/privacy-page/sections/{section}/toggle', [AdminPrivacyPageController::class, 'toggleSection'])->name('privacy_page.sections.toggle');

    // Refund Policy Page Editor
    Route::get('/refund-policy-page', [AdminRefundPolicyPageController::class, 'index'])->name('refund_policy.index');
    Route::post('/refund-policy-page', [AdminRefundPolicyPageController::class, 'update'])->name('refund_policy.update');
    Route::post('/refund-policy-page/sections/reorder', [AdminRefundPolicyPageController::class, 'reorderSections'])->name('refund_policy.sections.reorder');
    Route::post('/refund-policy-page/sections', [AdminRefundPolicyPageController::class, 'storeSection'])->name('refund_policy.sections.store');
    Route::put('/refund-policy-page/sections/{section}', [AdminRefundPolicyPageController::class, 'updateSection'])->name('refund_policy.sections.update');
    Route::delete('/refund-policy-page/sections/{section}', [AdminRefundPolicyPageController::class, 'destroySection'])->name('refund_policy.sections.destroy');
    Route::post('/refund-policy-page/sections/{section}/toggle', [AdminRefundPolicyPageController::class, 'toggleSection'])->name('refund_policy.sections.toggle');

    // Terms of Service Page Editor
    Route::get('/terms-page', [AdminTermsPageController::class, 'index'])->name('terms_page.index');
    Route::post('/terms-page', [AdminTermsPageController::class, 'update'])->name('terms_page.update');
    Route::post('/terms-page/sections/reorder', [AdminTermsPageController::class, 'reorderSections'])->name('terms_page.sections.reorder');
    Route::post('/terms-page/sections', [AdminTermsPageController::class, 'storeSection'])->name('terms_page.sections.store');
    Route::put('/terms-page/sections/{section}', [AdminTermsPageController::class, 'updateSection'])->name('terms_page.sections.update');
    Route::delete('/terms-page/sections/{section}', [AdminTermsPageController::class, 'destroySection'])->name('terms_page.sections.destroy');
    Route::post('/terms-page/sections/{section}/toggle', [AdminTermsPageController::class, 'toggleSection'])->name('terms_page.sections.toggle');

    // Home Page Editor
    Route::get('/home-page', [AdminHomePageController::class, 'index'])->name('home_page.index');
    Route::post('/home-page/meta', [AdminHomePageController::class, 'updateMeta'])->name('home_page.meta.update');
    Route::post('/home-page/hero', [AdminHomePageController::class, 'updateHero'])->name('home_page.hero.update');
    Route::post('/home-page/highlights', [AdminHomePageController::class, 'updateHighlights'])->name('home_page.highlights.update');
    Route::post('/home-page/benefits', [AdminHomePageController::class, 'updateBenefits'])->name('home_page.benefits.update');
    Route::post('/home-page/steps', [AdminHomePageController::class, 'updateSteps'])->name('home_page.steps.update');
    Route::post('/home-page/cta', [AdminHomePageController::class, 'updateCta'])->name('home_page.cta.update');

    // Navigation Menu
    Route::get('/navigation', [AdminNavigationController::class, 'index'])->name('navigation.index');
    Route::get('/navigation/create', [AdminNavigationController::class, 'create'])->name('navigation.create');
    Route::post('/navigation', [AdminNavigationController::class, 'store'])->name('navigation.store');
    Route::post('/navigation/reorder', [AdminNavigationController::class, 'reorder'])->name('navigation.reorder');
    Route::get('/navigation/{navigation}/edit', [AdminNavigationController::class, 'edit'])->name('navigation.edit');
    Route::put('/navigation/{navigation}', [AdminNavigationController::class, 'update'])->name('navigation.update');
    Route::delete('/navigation/{navigation}', [AdminNavigationController::class, 'destroy'])->name('navigation.destroy');

    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [AdminSettingsController::class, 'updateGeneral'])->name('settings.general.update');
    Route::post('/settings/regional', [AdminSettingsController::class, 'updateRegional'])->name('settings.regional.update');
    Route::post('/settings/notifications', [AdminSettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    Route::put('/settings/password', [AdminSettingsController::class, 'updatePassword'])->name('password.update');
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

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // Check if user is admin
        if (!Auth::user()->is_admin) {
            Auth::logout();
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
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('admin.login');
})->name('admin.logout');

