# DealMindanao - Monorepo Setup Complete! 🎉

The project has been successfully restructured into a **monorepo** with separate frontend and backend applications.

## 📁 New Structure

```
dealmindanao/
├── backend/                # Laravel API (Port 8000)
│   ├── app/
│   │   └── Http/Controllers/Api/
│   │       ├── AuthController.php
│   │       ├── ProductController.php
│   │       ├── CategoryController.php
│   │       └── OrderController.php
│   ├── routes/api.php
│   ├── config/cors.php
│   └── ...
├── frontend/               # HTML + Tailwind + Vite (Port 5173)
│   ├── src/
│   │   ├── css/app.css
│   │   └── js/
│   │       ├── api.js
│   │       ├── utils.js
│   │       └── main.js
│   ├── index.html
│   └── ...
├── docker-compose.yml      # Updated for monorepo
├── Dockerfile              # Backend-only Dockerfile
└── SETUP_GUIDE.md          # This file
```

## 🚀 Next Steps

### 1. Start Docker Containers

```bash
docker-compose up -d
```

This will start:
- **backend** service (Laravel API) on http://localhost:8000
- **frontend** service (Vite dev server) on http://localhost:5173
- **db** service (MySQL) on localhost:3306

### 2. Install Laravel Passport

Enter the backend container and install Passport:

```bash
# Enter backend container
docker-compose exec backend bash

# Install Passport via Composer
composer require laravel/passport

# Run migrations
php artisan migrate

# Install Passport (creates encryption keys)
php artisan passport:install

# (Optional) Create personal access client
php artisan passport:client --personal

# Exit container
exit
```

### 3. Update Backend .env

Add these lines to `backend/.env`:

```env
# Frontend URL for CORS
FRONTEND_URL=http://localhost:5173

# Passport keys will be auto-generated
```

### 4. Create Frontend .env

Create `frontend/.env`:

```env
VITE_API_URL=http://localhost:8000/api
```

### 5. Install Frontend Dependencies

```bash
cd frontend
npm install
```

Or if using Docker, it's already handled by docker-compose.

### 6. Run Migrations and Seeders

```bash
docker-compose exec backend php artisan migrate --seed
```

### 7. Test the API

```bash
# Test products endpoint
curl http://localhost:8000/api/products

# Test categories endpoint
curl http://localhost:8000/api/categories
```

### 8. Access the Applications

Frontend: **http://localhost:5173**  
Backend API: **http://localhost:8000/api**  
Backend Admin (legacy Blade views): **http://localhost:8000/admin** (optional, can be removed)

## 📋 What Was Created

### Backend API Endpoints

#### Authentication (Public)
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout (requires auth)

#### Products (Public read, Admin write)
- `GET /api/products` - List products (supports filters: category, company, search, min_price, max_price, discount_only, featured)
- `GET /api/products/{id}` - Get product details
- `POST /api/products` - Create product (admin only)
- `PUT /api/products/{id}` - Update product (admin only)
- `DELETE /api/products/{id}` - Delete product (admin only)

#### Categories (Public read, Admin write)
- `GET /api/categories` - List categories
- `POST /api/categories` - Create category (admin only)
- `PUT /api/categories/{id}` - Update category (admin only)
- `DELETE /api/categories/{id}` - Delete category (admin only)

#### Orders (Authenticated users)
- `GET /api/orders` - List user's orders
- `GET /api/orders/{id}` - Get order details
- `POST /api/orders` - Create order
- `PUT /api/orders/{id}` - Update order status (admin only)
- `DELETE /api/orders/{id}` - Cancel pending order

### Frontend Files

- **index.html** - Homepage with featured products
- **src/css/app.css** - Tailwind CSS with design system
- **src/js/api.js** - API client with Axios interceptors
- **src/js/utils.js** - Helper functions (formatPrice, showToast, etc.)
- **src/js/main.js** - Homepage JavaScript
- **vite.config.js** - Vite configuration with API proxy
- **tailwind.config.js** - Tailwind with brand colors

### Configuration Files

- **backend/config/cors.php** - CORS configuration for frontend
- **backend/routes/api.php** - All API routes
- **backend/app/Models/User.php** - Updated with HasApiTokens trait
- **docker-compose.yml** - Separate services for backend, frontend, db
- **Dockerfile** - Backend-only Docker image

## 🔧 Configuration Updates Needed

### Backend (.env)

Ensure these are set in `backend/.env`:

```env
APP_NAME="DealMindanao APP"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db            # Docker service name
DB_PORT=3306
DB_DATABASE=dealmindanao
DB_USERNAME=user
DB_PASSWORD=password

FRONTEND_URL=http://localhost:5173

# Passport will auto-generate these
PASSPORT_PRIVATE_KEY=
PASSPORT_PUBLIC_KEY=
```

### Frontend (.env)

Create `frontend/.env`:

```env
VITE_API_URL=http://localhost:8000/api
```

## 🎨 Design System

The frontend uses a consistent design system with:

- **Brand Color**: Green (#22c55e)
- **Component Classes**: btn-primary, btn-secondary, btn-danger, card, badge-*, alert-*
- **Layout Helpers**: page-shell (max-w-7xl container), page-section (spacing)
- **Typography**: h1, h2, h3, p, label with consistent sizing

## 🔐 Authentication Flow

1. User registers/logs in via `POST /api/auth/login`
2. Backend returns JWT token via Passport
3. Frontend stores token in localStorage
4. Token sent with every request via Authorization header
5. Backend validates token and returns user data

## 📦 TODO: Remaining Pages

You'll need to create these HTML pages in frontend:

- `shop.html` - Product listing with filters
- `product.html` - Product detail page
- `checkout.html` - Checkout flow
- `cart.html` - Shopping cart
- `dashboard.html` - User dashboard
- `profile.html` - User profile
- `auth/login.html` - Login page
- `auth/register.html` - Registration page

Each page should import:
- `src/css/app.css` for styles
- `src/js/api.js` for API calls
- `src/js/utils.js` for helper functions

## 🐛 Troubleshooting

### Backend API not responding
```bash
# Check backend logs
docker-compose logs backend

# Restart backend
docker-compose restart backend
```

### Frontend not loading
```bash
# Check frontend logs
docker-compose logs frontend

# Install dependencies manually
docker-compose exec frontend npm install

# Restart frontend
docker-compose restart frontend
```

### CORS errors
- Ensure FRONTEND_URL in backend/.env matches your frontend URL
- Check backend/config/cors.php has correct allowed_origins
- Restart backend after .env changes

### Database connection errors
```bash
# Check if DB is running
docker-compose ps

# Enter DB container
docker-compose exec db mysql -uuser -ppassword dealmindanao

# Run migrations again
docker-compose exec backend php artisan migrate:fresh --seed
```

## 📚 Resources

- Laravel Passport Docs: https://laravel.com/docs/11.x/passport
- Vite Docs: https://vitejs.dev/
- Tailwind CSS: https://tailwindcss.com/

## ✅ Checklist

- [x] Monorepo structure created
- [x] Laravel moved to backend/
- [x] Frontend scaffolded with Vite + Tailwind
- [x] API routes created
- [x] API controllers created (Auth, Products, Categories, Orders)
- [x] CORS configured
- [x] User model updated with HasApiTokens
- [x] Docker-compose updated for monorepo
- [ ] Laravel Passport installed (run Step 2)
- [ ] Backend .env updated (run Step 3)
- [ ] Frontend .env created (run Step 4)
- [ ] Frontend dependencies installed (run Step 5)
- [ ] Migrations run (run Step 6)
- [ ] Create remaining frontend HTML pages
- [ ] Test API endpoints
- [ ] Test frontend-backend integration

---

**Ready to proceed!** Follow steps 1-8 above to complete the setup.
