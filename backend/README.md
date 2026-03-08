# DealMindanao Backend README

Laravel API backend for DealMindanao e-commerce platform.

## Tech Stack

- **PHP**: 8.2+
- **Framework**: Laravel 12
- **Database**: MySQL 8.0
- **Authentication**: Laravel Passport (OAuth2)
- **API**: RESTful JSON API

## Installation

### Using Docker (Recommended)

```bash
# From project root
docker-compose up -d

# Enter backend container
docker-compose exec backend bash

# Install dependencies
composer install

# Run migrations
php artisan migrate

# Install Passport
php artisan passport:install

# (Optional) Seed database
php artisan db:seed
```

### Local Installation

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Configure database in .env
# Then run migrations
php artisan migrate

# Install Passport
php artisan passport:install

# Start server
php artisan serve
```

## Environment Variables

Create `.env` file:

```env
APP_NAME="DealMindanao APP"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=dealmindanao
DB_USERNAME=user
DB_PASSWORD=password

FRONTEND_URL=http://localhost:5173
```

## API Endpoints

### Authentication

- `POST /api/auth/register` - Register user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user

### Products

- `GET /api/products` - List products
- `GET /api/products/{id}` - Get product
- `POST /api/products` - Create product (admin)
- `PUT /api/products/{id}` - Update product (admin)
- `DELETE /api/products/{id}` - Delete product (admin)

Query parameters for listing:
- `category` - Filter by category ID
- `company` - Filter by company ID
- `search` - Search by product name
- `min_price` - Minimum price filter
- `max_price` - Maximum price filter
- `discount_only` - Show only discounted products (true/false)
- `featured` - Show only featured products (true/false)
- `sort_by` - Sort field (default: created_at)
- `sort_order` - Sort direction (asc/desc)
- `per_page` - Items per page (default: 15)

### Categories

- `GET /api/categories` - List categories
- `POST /api/categories` - Create category (admin)
- `PUT /api/categories/{id}` - Update category (admin)
- `DELETE /api/categories/{id}` - Delete category (admin)

### Orders

- `GET /api/orders` - List user's orders
- `GET /api/orders/{id}` - Get order details
- `POST /api/orders` - Create order
- `PUT /api/orders/{id}` - Update order status (admin)
- `DELETE /api/orders/{id}` - Cancel pending order

## Authentication

All protected endpoints require Bearer token:

```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://localhost:8000/api/user
```

## CORS Configuration

CORS is configured to allow requests from:
- http://localhost:5173 (Frontend dev server)
- http://localhost:3000
- Custom URL via FRONTEND_URL env variable

See `config/cors.php` for details.

## Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter ProductTest
```

## Artisan Commands

```bash
# Create API controller
php artisan make:controller Api/ProductController --api

# Create model with migration
php artisan make:model Product -m

# Create migration
php artisan make:migration create_products_table

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migrations with seed
php artisan migrate:fresh --seed
```

## Project Structure

```
backend/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           ├── AuthController.php
│   │           ├── ProductController.php
│   │           ├── CategoryController.php
│   │           └── OrderController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   └── OrderItem.php
│   └── Services/
├── config/
│   ├── cors.php
│   ├── auth.php
│   └── database.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── api.php
│   └── web.php
├── .env
└── composer.json
```

## Security

- Authentication via Laravel Passport
- CSRF protection enabled
- SQL injection protection via Eloquent ORM
- XSS protection via input validation
- Rate limiting on API endpoints

## Performance

- Database query optimization with eager loading
- API response caching (TODO)
- Database indexing on frequently queried columns
- Pagination for large datasets

## Deployment

1. Build Docker image
2. Set production environment variables
3. Run migrations
4. Install Passport keys
5. Deploy to server

See deployment documentation for detailed instructions.
