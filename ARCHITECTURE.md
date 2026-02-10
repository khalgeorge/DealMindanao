# DealMindanao - Monorepo Architecture

## 🏗️ Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                     DealMindanao Monorepo                    │
└─────────────────────────────────────────────────────────────┘
                             │
         ┌───────────────────┴───────────────────┐
         │                                       │
         ▼                                       ▼
┌──────────────────┐                    ┌──────────────────┐
│     Frontend     │◄──────HTTP────────►│     Backend      │
│  (Port 5173)     │                    │   (Port 8000)    │
│                  │                    │                  │
│ • HTML5          │                    │ • Laravel 12     │
│ • Tailwind CSS   │                    │ • PHP 8.2        │
│ • Vite           │                    │ • Passport Auth  │
│ • Axios          │                    │ • RESTful API    │
│ • Vanilla JS     │                    │ • JSON Responses │
└──────────────────┘                    └──────────────────┘
                                                 │
                                                 │
                                                 ▼
                                        ┌──────────────────┐
                                        │     MySQL DB     │
                                        │   (Port 3306)    │
                                        │                  │
                                        │ • Products       │
                                        │ • Categories     │
                                        │ • Orders         │
                                        │ • Users          │
                                        └──────────────────┘
```

## 🔄 Request Flow

```
User Browser
    │
    │ (1) HTTP Request
    ▼
Frontend (Vite Dev Server)
    │
    │ (2) API Call with Bearer Token
    ▼
Backend (Laravel API)
    │
    ├─► (3a) Auth Check (Passport)
    │
    ├─► (3b) CORS Validation
    │
    ├─► (3c) Route Handler
    │
    ├─► (3d) Controller Method
    │
    ├─► (3e) Database Query (Eloquent)
    │
    ▼
MySQL Database
    │
    │ (4) Data Response
    ▼
Backend (Laravel API)
    │
    │ (5) JSON Response
    ▼
Frontend JS
    │
    │ (6) DOM Update
    ▼
User sees updated page
```

## 📁 Directory Structure

```
dealmindanao/
│
├── backend/                         # Laravel API Backend
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   └── Api/            # API Controllers
│   │   │   │       ├── AuthController.php
│   │   │   │       ├── ProductController.php
│   │   │   │       ├── CategoryController.php
│   │   │   │       └── OrderController.php
│   │   │   └── Middleware/
│   │   ├── Models/
│   │   │   ├── User.php           # HasApiTokens trait
│   │   │   ├── Product.php
│   │   │   ├── Category.php
│   │   │   ├── Order.php
│   │   │   └── OrderItem.php
│   │   └── Services/
│   ├── config/
│   │   ├── cors.php               # CORS configuration
│   │   ├── auth.php
│   │   └── database.php
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   │   ├── api.php                # API routes only
│   │   └── web.php
│   ├── storage/
│   ├── .env                       # Backend environment vars
│   ├── .env.example
│   ├── composer.json
│   └── README.md
│
├── frontend/                       # HTML + Tailwind Frontend
│   ├── src/
│   │   ├── css/
│   │   │   └── app.css            # Tailwind + Design System
│   │   └── js/
│   │       ├── api.js             # Axios API client
│   │       ├── utils.js           # Helper functions
│   │       └── main.js            # Homepage logic
│   ├── auth/
│   │   ├── login.html             # (To be created)
│   │   └── register.html          # (To be created)
│   ├── index.html                 # Homepage ✅
│   ├── shop.html                  # (To be created)
│   ├── product.html               # (To be created)
│   ├── checkout.html              # (To be created)
│   ├── dashboard.html             # (To be created)
│   ├── profile.html               # (To be created)
│   ├── .env                       # Frontend environment vars
│   ├── .env.example
│   ├── package.json
│   ├── vite.config.js
│   ├── tailwind.config.js
│   └── README.md
│
├── docker-compose.yml             # Multi-service Docker config
├── Dockerfile                     # Backend Docker image
├── SETUP_GUIDE.md                 # Step-by-step setup ✅
├── ARCHITECTURE.md                # This file ✅
├── cleanup.ps1                    # Optional cleanup script
└── README.md                      # Project overview
```

## 🔑 Key Technologies

### Backend
| Technology | Purpose | Version |
|-----------|---------|---------|
| Laravel | PHP Framework | 12.x |
| PHP | Server Language | 8.2+ |
| MySQL | Database | 8.0 |
| Passport | OAuth2 Authentication | Latest |
| Composer | Dependency Manager | 2.x |

### Frontend
| Technology | Purpose | Version |
|-----------|---------|---------|
| Vite | Build Tool | 7.x |
| Tailwind CSS | Styling | 4.x |
| Axios | HTTP Client | 1.x |
| JavaScript | Logic | ES6+ |

### DevOps
| Technology | Purpose | Version |
|-----------|---------|---------|
| Docker | Containerization | Latest |
| Docker Compose | Multi-container | Latest |
| Apache | Web Server | 2.4 |

## 🌐 API Integration

### Authentication Flow

```
1. User submits login form
   ↓
2. Frontend: POST /api/auth/login {email, password}
   ↓
3. Backend: Validate credentials
   ↓
4. Backend: Generate OAuth2 token (Passport)
   ↓
5. Backend: Return {user, token}
   ↓
6. Frontend: Store token in localStorage
   ↓
7. Frontend: Include token in all subsequent requests
   Header: "Authorization: Bearer {token}"
```

### Example API Call

```javascript
// src/js/api.js
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add token to requests
api.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Get products
const products = await api.get('/products', {
  params: { category: 1, discount_only: true }
});
```

## 🎨 Design System

### Color Palette
```css
/* Brand Green */
--brand-50:  #f0fdf4;
--brand-500: #22c55e;  /* Primary */
--brand-600: #16a34a;  /* Hover */
--brand-700: #15803d;  /* Active */

/* Gray Scale */
--gray-50:  #f9fafb;
--gray-100: #f3f4f6;
--gray-500: #6b7280;
--gray-900: #111827;
```

### Component Classes
```css
.btn-primary      /* Green button */
.btn-secondary    /* White button */
.btn-danger       /* Red button */
.card             /* White card with shadow */
.badge-success    /* Green badge */
.alert-success    /* Green alert */
.page-shell       /* Max-width container */
.page-section     /* Spacing wrapper */
```

## 🔒 Security Features

1. **Authentication**: Laravel Passport OAuth2 tokens
2. **Authorization**: Role-based access control (admin, user)
3. **CORS**: Configured to allow frontend origin only
4. **CSRF**: Protected by Laravel middleware
5. **SQL Injection**: Prevented by Eloquent ORM
6. **XSS**: Sanitized inputs and outputs
7. **Rate Limiting**: API throttling (TODO)

## 📊 Database Schema

```sql
users
  - id
  - name
  - email
  - password
  - is_admin
  - created_at
  - updated_at

products
  - id
  - name
  - description
  - price
  - sale_price
  - category_id (FK)
  - company_id (FK)
  - image_url
  - stock
  - is_featured
  - created_at
  - updated_at

categories
  - id
  - name
  - description
  - created_at
  - updated_at

orders
  - id
  - user_id (FK)
  - total
  - status (pending, processing, shipped, delivered, cancelled)
  - shipping_address
  - shipping_city
  - shipping_province
  - shipping_postal_code
  - phone
  - notes
  - payment_method (gcash, cod, bank_transfer)
  - tracking_number
  - created_at
  - updated_at

order_items
  - id
  - order_id (FK)
  - product_id (FK)
  - quantity
  - price
  - created_at
  - updated_at
```

## 🚀 Deployment Strategy

### Backend (Laravel API)

**Option 1: Docker**
```bash
docker build -t dealmindanao-backend ./backend
docker run -p 8000:80 dealmindanao-backend
```

**Option 2: Traditional Hosting**
- Deploy to VPS (DigitalOcean, AWS EC2, etc.)
- Configure Apache/Nginx
- Run migrations
- Install Passport keys

### Frontend (Static Site)

**Option 1: CDN/Static Hosting**
```bash
cd frontend
npm run build
# Deploy dist/ folder to:
# - Vercel
# - Netlify
# - AWS S3 + CloudFront
# - Cloudflare Pages
```

**Option 2: Same Server**
- Serve from backend's public/ folder
- Configure Apache to serve frontend files

## 📈 Performance Considerations

1. **Frontend**
   - Code splitting with Vite
   - Image optimization
   - Lazy loading
   - CDN for assets

2. **Backend**
   - Database query optimization
   - Eager loading relationships
   - Response caching
   - Database indexing
   - Queue jobs for emails

3. **Infrastructure**
   - Load balancing
   - Database replication
   - Redis caching
   - CDN for static assets

## 🧪 Testing Strategy

### Backend Tests
```bash
php artisan test
```
- Unit tests for models
- Feature tests for API endpoints
- Authentication tests
- Authorization tests

### Frontend Tests
```bash
npm run test
```
- Component tests
- Integration tests
- E2E tests with Playwright

## 📝 Development Workflow

1. **Start Docker**: `docker-compose up -d`
2. **Backend changes**: Edit in `backend/`, auto-reloaded
3. **Frontend changes**: Edit in `frontend/`, hot-reloaded by Vite
4. **Test API**: Use Postman or curl
5. **Commit**: Git commit changes
6. **Deploy**: Follow deployment strategy

---

**Created**: February 10, 2026  
**Version**: 1.0.0  
**Architecture**: Monorepo with standalone Frontend & Backend
