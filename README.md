# DealMindanao

DealMindanao is a curated local deals marketplace for Mindanao.  
Customers browse deals and submit order requests. Payments are handled offline (GCash / COD / Bank).  
DealMindanao does not process online payments.

---

## 🚀 Tech Stack

- Backend: Laravel 11
- Frontend: Blade + Tailwind CSS + Vite
- Database: MySQL
- Cart: Session-based
- Email: SMTP (Admin + User notifications)
- Hosting: Apache/Nginx

---

## 🎯 Core Features

- Admin-only backend
- Product management (CRUD)
- Company & Category management
- Shop deals listing
- Product detail page
- Cart (session-based)
- Checkout (order request only)
- Orders management
- Email notifications (admin + user)
- No online payment (offline payment only)

---

## 🔁 User Flow

Shop Deals → View Product → Add to Cart → Checkout → Submit Order

After submit:
- Admin receives order email
- User receives confirmation email
- Admin contacts user
- Offline payment
- Manual fulfillment

---

## 📂 Project Structure

---------------------------

app/
Http/Controllers/
Admin/
CartController.php
CheckoutController.php
Models/
Product.php
Company.php
Category.php
Order.php
OrderItem.php

resources/
views/
layouts/
app.blade.php
admin.blade.php
shop.blade.php
product.blade.php
cart.blade.php
checkout.blade.php
checkout-success.blade.php
admin/
products/
orders/
companies/
categories/


---------------------------

## ⚙️ Local Setup

```bash
git clone <your-repo>
cd dealmindanao
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run dev
php artisan serve



---------------------------
Email Setup

Update .env:
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@dealmindanao.com
MAIL_FROM_NAME="DealMindanao"

---------------------------
⚠️ Legal Disclaimer

DealMindanao is a curated deals listing platform.
Payments are handled offline between customers and partners.
DealMindanao does not process online payments.



---------------------------
Recommended Tests Before Launch
Add product from admin
Product appears on shop page
Add to cart
Checkout submit
Order saved in DB
Admin receives email
User receives confirmation email
Admin can update order status



---------------------------

---

# 🧩 SYSTEM DOCUMENTATION (Internal / Technical)

```md
# DealMindanao – System Documentation

## 1. Overview

DealMindanao is a Laravel-based curated marketplace where admins publish deals and users place order requests.  
The system does not process online payments. All payments are handled offline.

---

## 2. Architecture

### 2.1 Application Layers

- Presentation Layer: Blade + Tailwind (UI)
- Application Layer: Controllers (Admin, Cart, Checkout)
- Domain Layer: Eloquent Models
- Infrastructure Layer: MySQL, SMTP Email

---

## 3. Core Entities

### User (Admin only)
- id
- name
- email
- password

### Product
- id
- name
- slug
- description
- price
- discount
- images (JSON)
- company_id
- category_id
- is_featured
- is_active

### Company
- id
- name
- city
- contact_email
- contact_phone
- messenger_link
- logo

### Category
- id
- name
- slug

### Order
- id
- order_number
- customer_name
- email
- phone
- address
- status (pending, contacted, processing, completed)
- notes

### OrderItem
- id
- order_id
- product_id
- product_name
- price
- qty

---

## 4. Functional Flow

### 4.1 Public Flow

1. User visits /shop
2. User views product (/shop/{slug})
3. User adds product to cart (session)
4. User goes to /cart
5. User proceeds to /checkout
6. User submits order request
7. System saves order + order items
8. System sends email to admin + user
9. System redirects to /checkout/success

---

### 4.2 Admin Flow

1. Admin logs in (/admin/login)
2. Admin manages:
   - Products
   - Companies
   - Categories
3. Admin views orders
4. Admin updates order status
5. Admin contacts customer manually
6. Admin completes fulfillment offline

---

## 5. Security

- Admin routes protected by auth middleware
- CSRF protection on forms
- File upload validation (images only)
- No sensitive payment data stored
- Email credentials stored in .env

---

## 6. Deployment Checklist

- Set APP_ENV=production
- Set APP_DEBUG=false
- Configure MAIL_* credentials
- Configure DB credentials
- Run migrations
- Run storage:link
- Set proper file permissions
- Enable SSL
- Set up backups
- Enable logging

---

## 7. Risks & Mitigations

| Risk | Mitigation |
|------|------------|
Spam orders | Add CAPTCHA later |
Fake info | Admin manual verification |
Email delivery failure | Fallback contact via phone |
High traffic | Enable caching + pagination |

---

## 8. Future Enhancements

- Online payments
- SMS notifications
- Vendor login (multi-vendor)
- Order tracking page for users
- Analytics dashboard
