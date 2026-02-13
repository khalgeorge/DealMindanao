# DealMindanao - Frontend Development Guide

## Frontend Architecture

**Vanilla JS + Tailwind CSS + Vite** - No framework, component-based architecture
- **Build Tool**: Vite 7 with hot module replacement
- **Styling**: Tailwind CSS 4 with custom design system (`@theme` in CSS)
- **API Client**: Axios with Bearer token authentication
- **State**: localStorage for auth, no global state management
- **Structure**: Multi-page app with shared components (navbar, footer, sidebar)

## Critical Development Commands

```bash
# Start frontend dev server (from project root)
cd frontend && npm install && npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Start entire stack (includes backend API)
docker-compose up -d
```

## Directory Structure

```
frontend/
├── index.html, shop.html, cart.html...    # Public pages
├── admin/                                  # Admin dashboard pages
│   ├── dashboard.html, products.html...
│   └── login.html
├── src/
│   ├── css/
│   │   └── app.css                        # Tailwind config + custom utilities
│   └── js/
│       ├── api.js                         # Axios instance + API methods
│       ├── layout.js                      # Auto-initialize components
│       ├── utils.js                       # Helper functions
│       └── components/                    # Reusable components
│           ├── navbar.js                  # createNavbar(), initNavbar()
│           ├── footer.js                  # createFooter()
│           └── admin-sidebar.js           # createAdminSidebar(), checkAuth()
├── vite.config.js                         # Multi-page entry points
└── tailwind.config.js                     # Tailwind theme
```

## Component Pattern

**String-based components** - Functions return HTML strings, inserted via `innerHTML`:
```javascript
// Example: navbar.js
export function createNavbar() {
  return `<nav class="bg-white shadow-sm">...</nav>`;
}

export function initNavbar() {
  // Add event listeners after DOM insertion
  document.getElementById('menu-toggle')?.addEventListener('click', ...);
}

// Usage in layout.js
document.getElementById('navbar').innerHTML = createNavbar();
initNavbar();
```

**All pages include**:
```html
<div id="navbar"></div>  <!-- Auto-populated by layout.js -->
<main><!-- Page content --></main>
<div id="footer"></div>   <!-- Auto-populated by layout.js -->
<script type="module" src="/src/js/layout.js"></script>
```

## Authentication Pattern

**Bearer Token + localStorage**:
- Token stored in `localStorage.getItem('auth_token')`
- User object stored in `localStorage.getItem('user')` (JSON string)
- Axios interceptor auto-adds `Authorization: Bearer {token}` header ([api.js](frontend/src/js/api.js#L17-L26))
- 401 response triggers logout and redirect to login

```javascript
// Login flow
import { auth } from '/src/js/api.js';
const data = await auth.login(email, password);
// Token automatically stored, axios configured

// Check if authenticated
if (!auth.isAuthenticated()) {
  window.location.href = '/login.html';
}
```

## Design System

**Tailwind CSS 4 with `@theme`** - Custom utilities defined in [app.css](frontend/src/css/app.css):
```css
/* Custom color palette */
@theme {
  --color-brand-500: #22c55e;   /* Primary green */
  --color-brand-600: #16a34a;   /* Hover green */
  --color-accent-500: #f59e0b;  /* Accent amber */
}

/* Reusable button utilities */
@utility btn-primary {
  @apply px-5 py-2.5 bg-brand-600 hover:bg-brand-700 
         text-white text-sm font-medium rounded-lg shadow-sm;
}
```

**Key utility classes** (defined in app.css):
- `.btn-primary`, `.btn-secondary`, `.btn-outline`, `.btn-danger`
- `API Integration

**Centralized API client** in [api.js](frontend/src/js/api.js) with organized methods:
```javascript
// Import the organized APIs
import { auth, products, categories, orders } from '/src/js/api.js';

// Auth methods
await auth.login(email, password);
await auth.register(userData);
auth.isAuthenticated();  // Returns boolean
const user = auth.getUser();  // Returns parsed user object

// Products methods
const allProducts = await products.getAll({ category: 1 });
conFrontend-Specific Patterns

1. **Multi-Page App with Vite**: Each HTML page is an entry point in [vite.config.js](frontend/vite.config.js#L19-L36)
   - When adding a new page, add it to `rollupOptions.input`
   - Example: `newPage: resolve(__dirname, 'new-page.html')`

2. **Layout Auto-Initialization**: [layout.js](frontend/src/js/layout.js) runs on every page
   - Automatically inserts navbar/footer from `#navbar` and `#footer` divs
   - Determines page type (admin vs public) from URL path
   - Admin pages: checks auth via `checkAuth()`, redirects if unauthorized

3. **Admin Route Protection**: 
   ```javascript
   // In admin pages, layout.js calls:
   if (!checkAuth()) {
     window.location.href = '/admin/login.html';
   }
   ```

4. **ImageFrontend Tasks

**Adding a New Page**:
1. Create HTML file: `frontend/new-page.html`
   ```html
   <!DOCTYPE html>
   <html lang="en">
   <head>
     <meta charset="UTF-8">
     <title>Page Title</title>
     <link rel="stylesheet" href="/src/css/app.css">
   </head>
   <body>
     <div id="navbar"></div>
     <main><!-- Content --></main>
     <div id="footer"></div>
     <script type="module" src="/src/js/layout.js"></script>
   </body>
   </html>
   Debugging

**Browser DevTools**:
- **Console**: Check for JS errors, `console.log()` statements
- **Network**: Inspect API calls, check request/response headers and payloads
- **Application > Local Storage**: View `auth_token` and `user` data
- **Sources**: Set breakpoints in ES modules

**Common Issues**:
- **CORS errors**: Check `FRONTEND_URL` in backend `.env` matches frontend port
- **401 Unauthorized**: Token expired or invalid, check localStorage
- **Module import errors**: Ensure paths start with `/` or `./`, include `.js` extension
- *Styling Guidelines

**Use Tailwind utility classes first**, custom CSS only when needed:
```html
<!-- Good: Tailwind utilities -->
<button class="btn-primary">Click me</button>
<div class="flex items-center gap-4 p-6 bg-white rounded-lg shadow-sm">

<!-- Avoid: Inline styles or custom CSS classes for simple things -->
<div style="display: flex; gap: 16px;">...</div>
```

**Responsive design** - Mobile first:
```html
<!-- Default mobile, then tablet (md:), desktop (lg:) -->
<div class="text-sm md:text-base lg:text-lg">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
```

**Custom utilities** defined in [app.css](frontend/src/css/app.css):
- Use `@utility` for reusable component styles (buttons, badges)
- Use `@layer base` for global element styles (typography)
- Color variables use `--color-brand-*` and `--color-accent-*`

## Key Frontend Files

- **Entry point**: [layout.js](frontend/src/js/layout.js) - Auto-runs on all pages
- **API client**: [api.js](frontend/src/js/api.js) - Centralized axios instance & methods
- **Styles**: [app.css](frontend/src/css/app.css) - Tailwind config + custom utilities
- **Build config**: [vite.config.js](frontend/vite.config.js) - Multi-page entries
- **Components**: [navbar.js](frontend/src/js/components/navbar.js), [footer.js](frontend/src/js/components/footer.js), [admin-sidebar.js](frontend/src/js/components/admin-sidebar.js
   ```

**Adding API Method**:
1. Add to [api.js](frontend/src/js/api.js) in appropriate section:
   ```javascript
   export const products = {
     async getAll() { ... },
     async myNewMethod(params) {
       const response = await api.get('/products/custom', { params });
       return response.data;
     }
   };
   ```
2. **Admin vs User Routes**: 
   - Public: `GET /api/products`, `GET /api/categories`
   - Authenticated: `POST /api/orders`
   - Admin only: `POST /api/products`, `PUT /api/products/{id}`, etc.

3. **Image Storage**: Products use JSON array for images, not single URL
   ```php
   protected $fillable = ['images', ...];
   protected $casts = ['images' => 'array'];
   ```

4. **Meta Fields**: Products and Pages have SEO meta fields (`meta_title`, `meta_description`) added via later migrations

5. **AdminMiddleware**: Custom middleware at [backend/app/Http/Middleware/AdminMiddleware.php](backend/app/Http/Middleware/AdminMiddleware.php) checks `is_admin` boolean on User model

## Common Tasks

**Adding New API Endpoint**:
1. Create controller in `backend/app/Http/Controllers/Api/`
2. Add route to `backend/routes/api.php`
3. Apply middleware: `auth:api` for authenticated, `admin` for admin-only
4. Return JSON: `return response()->json($data);`

**Adding Frontend Page**:
1. Create HTML file in `frontend/` (e.g., `deals.html`)
2. Import shared CSS/JS: `<link rel="stylesheet" href="/src/css/app.css">`
3. Use API via `import { api } from './src/js/api.js'`
4. Update navigation if needed

**Database Changes**:
1. Create migration: `docker-compose exec backend php artisan make:migration create_xyz_table`
2. Run: `docker-compose exec backend php artisan migrate`
3. Update corresponding Model with `$fillable`, `$casts`, relationships

## Testing & Debugging

- **Backend logs**: `docker-compose logs -f backend`
- **API testing**: Use `curl` or Postman against `http://localhost:8000/api`
- **Frontend debugging**: Browser DevTools, check Network tab for API calls
- **DB access**: `docker-compose exec db mysql -u user -ppassword dealmindanao`

## Environment Setup

**Backend** `.env` must have:
```env
FRONTEND_URL=http://localhost:5173  # For CORS
DB_CONNECTION=mysql
DB_HOST=db                          # Docker service name
```

**Frontend** `.env` (Vite):
```env
VITE_API_URL=http://localhost:8000/api
```

## Key Files to Reference

- API routing: [backend/routes/api.php](backend/routes/api.php)
- CORS config: [backend/config/cors.php](backend/config/cors.php)
- Frontend API client: [frontend/src/js/api.js](frontend/src/js/api.js)
- Docker orchestration: [docker-compose.yml](docker-compose.yml)
- Architecture docs: [ARCHITECTURE.md](ARCHITECTURE.md)
