# Admin Panel - Development Guide

## 🎯 Admin System Overview

The admin panel provides full CRUD management for products, orders, categories, and companies.

---

## 🔐 Admin Access

### Login
- **URL**: http://localhost:8000/admin/login
- **Admin Email**: Create via seeder or manually set `is_admin = 1` in database

### Creating Admin User via Tinker:
```bash
docker-compose exec backend php artisan tinker

# Create admin user
User::create([
    'name' => 'Admin User',
    'email' => 'admin@dealmindanao.com',
    'password' => bcrypt('password'),
    'is_admin' => true
]);
```

---

## 📊 Dashboard

**Route**: `GET /admin`  
**Controller**: `Web\Admin\DashboardController@index`

**Features**:
- Total orders, products, categories, companies
- Total revenue calculation
- Recent orders list (10 most recent)
- Quick stats overview

---

## 📦 Products Management

### Routes:
```
GET    /admin/products              - List all products (with filters)
GET    /admin/products/create       - Create product form
POST   /admin/products              - Store new product
GET    /admin/products/{id}/edit    - Edit product form
PUT    /admin/products/{id}         - Update product
DELETE /admin/products/{id}         - Delete product
POST   /admin/products/{id}/toggle-status   - Toggle active/inactive
POST   /admin/products/{id}/toggle-featured - Toggle featured status
```

### Controller: `Web\Admin\ProductController`

### Validation: `ProductRequest`

**Fields**:
- `name` (required, max:255)
- `slug` (required, unique, lowercase with hyphens)
- `description` (optional, max:5000)
- `price` (required, numeric, min:0)
- `discount` (optional, numeric, min:0)
- `stock_quantity` (required, integer, min:0)
- `sku` (optional, unique, max:100)
- `category_id` (required, exists)
- `company_id` (required, exists)
- `images` (array, max 10 images)
- `is_active` (boolean)
- `is_featured` (boolean)
- `meta_title` (optional, SEO)
- `meta_description` (optional, SEO)

### Features:
- **Search**: Name, slug, or SKU
- **Filters**: Category, company, status
- **Pagination**: 15 per page
- **Auto-slug**: Generated from product name if not provided
- **Image upload**: Supports up to 10 images
- **Quick actions**: Toggle active/featured status

### Views:
- `admin/products/index.blade.php` - Product listing
- `admin/products/create.blade.php` - Create form
- `admin/products/edit.blade.php` - Edit form
- `admin/products/form.blade.php` - Shared form partial

---

## 📋 Orders Management

### Routes:
```
GET  /admin/orders           - List all orders (with filters)
GET  /admin/orders/{id}      - View order details
PUT  /admin/orders/{id}      - Update order (status, notes)
POST /admin/orders/{id}/status - Quick status update
```

### Controller: `Web\Admin\OrderController`

### Validation: `OrderUpdateRequest`

**Order Statuses**:
1. `pending` - New order, awaiting confirmation
2. `confirmed` - Order confirmed by admin
3. `processing` - Being prepared
4. `shipped` - On delivery
5. `delivered` - Completed
6. `cancelled` - Cancelled

### Features:
- **Search**: Order number, customer name, email
- **Filters**: Status, payment method
- **Pagination**: 15 per page
- **Order details**: Full breakdown with items, shipping info
- **Status management**: Update order status with notes
- **Customer info**: View customer details and contact

### Views:
- `admin/orders/index.blade.php` - Orders listing
- `admin/orders/show.blade.php` - Order detail view

---

## 🏷️ Categories Management

### Routes:
```
GET    /admin/categories              - List categories
GET    /admin/categories/create       - Create category form
POST   /admin/categories              - Store category
GET    /admin/categories/{id}/edit    - Edit category form
PUT    /admin/categories/{id}         - Update category
DELETE /admin/categories/{id}         - Delete category (if no products)
POST   /admin/categories/{id}/toggle-status - Toggle active status
```

### Controller: `Web\Admin\CategoryController`

### Validation: `CategoryRequest`

**Fields**:
- `name` (required, max:255)
- `slug` (required, unique, lowercase with hyphens)
- `description` (optional, max:1000)
- `is_active` (boolean)

### Features:
- **Search**: Category name
- **Product count**: Shows number of products per category
- **Delete protection**: Cannot delete if has products
- **Auto-slug**: Generated from name
- **Status toggle**: Quick activate/deactivate

### Views:
- `admin/categories/index.blade.php` - Category listing
- `admin/categories/create.blade.php` - Create form
- `admin/categories/edit.blade.php` - Edit form

---

## 🏢 Companies Management

### Routes:
```
GET    /admin/companies               - List companies
GET    /admin/companies/create        - Create company form
POST   /admin/companies               - Store company
GET    /admin/companies/{id}/edit     - Edit company form
PUT    /admin/companies/{id}          - Update company
DELETE /admin/companies/{id}          - Delete company (if no products)
POST   /admin/companies/{id}/toggle-status - Toggle active status
```

### Controller: `Web\Admin\CompanyController`

### Validation: `CompanyRequest`

**Fields**:
- `name` (required, max:255)
- `slug` (required, unique, lowercase with hyphens)
- `description` (optional, max:2000)
- `email` (optional, email, max:255)
- `phone` (optional, max:20)
- `address` (optional, max:500)
- `website` (optional, URL, max:255)
- `logo` (optional, max:500)
- `is_active` (boolean)

### Features:
- **Search**: Company name or email
- **Product count**: Shows number of products per company
- **Delete protection**: Cannot delete if has products
- **Contact info**: Email, phone, address management
- **Auto-slug**: Generated from name
- **Logo upload**: Company branding

### Views:
- `admin/companies/index.blade.php` - Company listing
- `admin/companies/create.blade.php` - Create form
- `admin/companies/edit.blade.php` - Edit form

---

## 🎨 Admin Layout & Components

### Main Layout
**File**: `resources/views/layouts/admin.blade.php`

**Includes**:
- Admin sidebar navigation
- Toast notifications
- Vite assets (CSS/JS)
- CSRF token

### Sidebar
**File**: `resources/views/partials/admin-sidebar.blade.php`

**Navigation**:
- Dashboard
- Products
- Orders
- Categories
- Companies
- Logout

### Active Route Highlighting:
```blade
@if(request()->routeIs('admin.products.*'))
    <!-- Add active class -->
@endif
```

---

## 🔧 Common Admin Tasks

### 1. Add New Product
```
1. Go to /admin/products
2. Click "+ Add Product"
3. Fill in:
   - Name, price, stock
   - Select category & company
   - Upload images (optional)
   - Add description & SEO
4. Submit
```

### 2. Process Order
```
1. Go to /admin/orders
2. Click on order number
3. Review order details
4. Update status:
   - pending → confirmed
   - confirmed → processing
   - processing → shipped
   - shipped → delivered
5. Add admin notes (optional)
```

### 3. Manage Category
```
1. Go to /admin/categories
2. Create/Edit category
3. Set name & description
4. Toggle active status
5. Cannot delete if has products
```

### 4. Toggle Product Status
```
1. Go to /admin/products
2. Use toggle buttons for:
   - Active/Inactive (visibility)
   - Featured/Normal (homepage display)
```

---

## 💾 Database Queries

### Get Order Statistics:
```php
$totalOrders = Order::count();
$totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
$pendingOrders = Order::where('status', 'pending')->count();
```

### Get Product Inventory:
```php
$lowStock = Product::where('stock_quantity', '<', 10)->count();
$outOfStock = Product::where('stock_quantity', 0)->count();
$activeProducts = Product::where('is_active', true)->count();
```

### Get Recent Activity:
```php
$recentOrders = Order::with('user')->latest()->take(10)->get();
$recentProducts = Product::latest()->take(5)->get();
```

---

## 🚨 Error Handling

### Flash Messages:
```php
// Success
return redirect()->route('admin.products.index')
    ->with('success', 'Product created!');

// Error
return back()->with('error', 'Cannot delete!');
```

### Display in Blade:
```blade
@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
@endif
```

---

## 🔒 Authorization

### Middleware Stack:
```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes
});
```

### Check in Controller:
```php
$this->middleware(['auth', 'admin']);
```

### AdminMiddleware:
**File**: `app/Http/Middleware/AdminMiddleware.php`

**Logic**:
```php
if (!auth()->check() || !auth()->user()->is_admin) {
    abort(403, 'Unauthorized');
}
```

---

## 📱 Responsive Design

The admin panel is fully responsive:
- **Desktop**: Full sidebar + content
- **Tablet**: Collapsible sidebar
- **Mobile**: Hamburger menu

Breakpoints (Tailwind):
- `sm:` 640px
- `md:` 768px
- `lg:` 1024px
- `xl:` 1280px

---

## ✅ Testing Checklist

### Products:
- [ ] Create product with all fields
- [ ] Edit product
- [ ] Delete product
- [ ] Toggle active status
- [ ] Toggle featured status
- [ ] Upload multiple images
- [ ] Search products
- [ ] Filter by category/company

### Orders:
- [ ] View order list
- [ ] View order details
- [ ] Update order status
- [ ] Add admin notes
- [ ] Search orders
- [ ] Filter by status

### Categories:
- [ ] Create category
- [ ] Edit category
- [ ] Delete empty category
- [ ] Attempt delete with products (should fail)
- [ ] Toggle status

### Companies:
- [ ] Create company
- [ ] Edit company
- [ ] Delete empty company
- [ ] Attempt delete with products (should fail)
- [ ] Toggle status

---

## 🎯 Next Steps

Ready to enhance? Consider:

1. **Bulk Actions**: Select multiple items for bulk updates
2. **Export**: CSV/Excel export for products/orders
3. **Analytics**: Charts for sales trends
4. **Notifications**: Real-time order notifications
5. **Media Library**: Centralized image management
6. **Activity Log**: Track admin actions
7. **Permissions**: Role-based access control
8. **API Integration**: Mobile admin app

---

## 🔗 Quick Links

- **Admin Dashboard**: http://localhost:8000/admin
- **Products**: http://localhost:8000/admin/products
- **Orders**: http://localhost:8000/admin/orders
- **Categories**: http://localhost:8000/admin/categories
- **Companies**: http://localhost:8000/admin/companies

Your admin panel is fully functional and ready for development! 🚀
