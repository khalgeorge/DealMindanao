# Laravel Integration Complete - Usage Guide

## ✅ All Todo Items Completed!

The Laravel integration is now fully complete with all features implemented.

---

## 🔐 Form Validation Requests

### Available Request Classes

All validation logic has been centralized into dedicated Form Request classes:

#### 1. **CheckoutRequest**
- Location: `app/Http/Requests/CheckoutRequest.php`
- Used for: Order checkout validation
- Fields: items, shipping info, payment method
- Usage in CheckoutController:
```php
public function store(CheckoutRequest $request)
{
    $validated = $request->validated();
    // Process order...
}
```

#### 2. **ProductRequest**
- Location: `app/Http/Requests/ProductRequest.php`
- Used for: Creating/updating products (admin)
- Features:
  - Auto-generates slug from name
  - Validates unique slug and SKU
  - Converts boolean checkboxes
  - Validates images (max 10)
  - SEO meta validation

#### 3. **CategoryRequest**
- Location: `app/Http/Requests/CategoryRequest.php`
- Used for: Creating/updating categories (admin)
- Features:
  - Auto-generates slug from name
  - Validates unique slug
  - Boolean conversion for is_active

#### 4. **CompanyRequest**
- Location: `app/Http/Requests/CompanyRequest.php`
- Used for: Creating/updating companies (admin)
- Features:
  - Auto-generates slug from name
  - Validates email, phone, website
  - URL validation for website field

#### 5. **OrderUpdateRequest**
- Location: `app/Http/Requests/OrderUpdateRequest.php`
- Used for: Updating order status (admin)
- Validates: status, admin_notes

---

## 🏷️ SEO Meta Helpers

### MetaHelper Class

Location: `app/Helpers/MetaHelper.php`

#### Global Helper Functions

```php
// Generate SEO title
meta_title('Product Name')  // Returns: "Product Name | DealMindanao"
meta_title('Home', false)   // Returns: "Home" (without site name)

// Generate meta description (auto-truncates to 160 chars)
meta_description($product->description)

// Generate meta keywords
meta_keywords(['hardware', 'tools', 'Mindanao'])
meta_keywords('hardware, tools, Mindanao')

// Get Open Graph image URL
og_image($product->images[0])  // Returns full URL
og_image()                      // Returns default logo

// Sanitize text (removes HTML, trims whitespace)
sanitize_meta($product->description)
```

#### Using in Blade Templates

```blade
@section('meta_title', meta_title($product->name))
@section('meta_description', meta_description($product->description))

@push('head')
<meta property="og:image" content="{{ og_image($product->images[0] ?? null) }}">
@endpush
```

#### Structured Data (JSON-LD)

```blade
@push('head')
<script type="application/ld+json">
{!! \App\Helpers\MetaHelper::productStructuredData($product) !!}
</script>
@endpush
```

#### Breadcrumb Structured Data

```php
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Shop', 'url' => '/shop'],
    ['name' => $product->name, 'url' => route('product.show', $product->slug)],
];
```

```blade
@push('head')
<script type="application/ld+json">
{!! \App\Helpers\MetaHelper::breadcrumbStructuredData($breadcrumbs) !!}
</script>
@endpush
```

---

## 🎨 Frontend Asset Compilation

### Development

```bash
cd backend
npm run dev
```

This starts Vite in development mode with hot module replacement.

### Production Build

```bash
cd backend
npm run build
```

This compiles and minifies all assets to `public/build/`.

### Asset Loading in Blade

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

Already included in `layouts/app.blade.php` and `layouts/admin.blade.php`.

---

## 🌐 Routes Overview

### Public Routes
- `GET /` - Home page
- `GET /shop` - Product listing with filters
- `GET /products/{slug}` - Product detail page
- `GET /cart` - Shopping cart
- `GET /about` - About page
- `GET /contact` - Contact page
- `GET /partner` - Partner page

### Protected Routes (requires auth)
- `GET /checkout` - Checkout page
- `POST /checkout` - Process order
- `GET /checkout/success/{order}` - Order confirmation
- `GET /account` - User dashboard with order history

### Admin Routes (prefix: /admin, requires auth + admin)
- `GET /admin` - Dashboard with stats
- `GET /admin/products` - Product management
- `GET /admin/orders` - Order management
- `GET /admin/categories` - Category management
- `GET /admin/companies` - Company management
- `GET /admin/login` - Admin login (guest only)
- `POST /admin/logout` - Admin logout

---

## 🐳 Docker Services

Your application runs via Docker Compose:

- **Backend (Laravel)**: http://localhost:8000
- **Database (MySQL)**: localhost:3306
- **Frontend (Node - can be disabled)**: localhost:5173

### Useful Docker Commands

```bash
# View logs
docker-compose logs -f backend

# Run artisan commands
docker-compose exec backend php artisan migrate
docker-compose exec backend php artisan tinker

# Access MySQL
docker-compose exec db mysql -u user -ppassword dealmindanao

# Restart services
docker-compose restart backend
```

---

## 📦 Database Seeders

To populate demo data:

```bash
docker-compose exec backend php artisan db:seed --class=CategorySeeder
```

---

## 🧪 Testing the Application

1. **Visit Homepage**: http://localhost:8000
2. **Browse Shop**: http://localhost:8000/shop
3. **View Product**: Click any product to see details
4. **Add to Cart**: Use the cart functionality
5. **Register/Login**: Create a user account
6. **Checkout**: Complete an order
7. **Admin Access**: 
   - Create admin user or use seeded admin
   - Visit http://localhost:8000/admin

---

## 📝 Validation Error Handling

Form Request classes automatically handle validation errors:

- **API Responses**: Returns JSON with 422 status
- **Web Responses**: Redirects back with errors in session
- **Access in Blade**:

```blade
@error('field_name')
    <span class="text-red-500 text-sm">{{ $message }}</span>
@enderror

{{-- Or --}}
@if($errors->has('field_name'))
    <div class="alert alert-error">{{ $errors->first('field_name') }}</div>
@endif
```

---

## 🔧 Configuration Files

- **Vite**: `backend/vite.config.js`
- **Tailwind**: `backend/tailwind.config.js`
- **PostCSS**: `backend/postcss.config.js`
- **Package**: `backend/package.json`
- **Composer**: `backend/composer.json` (includes helpers autoload)

---

## ✨ Next Steps

The Laravel integration is complete! You can now:

1. ✅ Test all public pages
2. ✅ Test the checkout flow
3. ✅ Test admin panel functionality
4. ✅ Customize Blade templates styling
5. ✅ Add more products via admin
6. ✅ Process orders
7. ✅ Deploy to production

All validation, SEO helpers, and asset compilation are ready to use!
