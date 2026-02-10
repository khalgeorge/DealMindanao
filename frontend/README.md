# DealMindanao Frontend

Standalone frontend application for DealMindanao built with HTML, Tailwind CSS, and Vite.

## Tech Stack

- **HTML5**: Semantic markup
- **Tailwind CSS v4**: Utility-first styling
- **Vite**: Fast build tool and dev server
- **Axios**: HTTP client for API calls
- **Vanilla JavaScript**: No framework, pure ES modules

## Getting Started

### Install Dependencies

```bash
npm install
```

### Development Server

```bash
npm run dev
```

The app will be available at `http://localhost:5173`

### Build for Production

```bash
npm run build
```

Built files will be in the `dist/` directory.

### Preview Production Build

```bash
npm run preview
```

## Project Structure

```
frontend/
├── src/
│   ├── css/
│   │   └── app.css          # Main stylesheet with Tailwind
│   └── js/
│       ├── api.js           # API client and endpoints
│       ├── utils.js         # Helper functions
│       └── main.js          # Homepage JavaScript
├── auth/
│   ├── login.html
│   └── register.html
├── index.html               # Homepage
├── shop.html                # Product listing
├── product.html             # Product details
├── checkout.html            # Checkout flow
├── dashboard.html           # User dashboard
├── profile.html             # User profile
├── package.json
├── vite.config.js
└── tailwind.config.js
```

## Environment Variables

Create a `.env` file:

```env
VITE_API_URL=http://localhost:8000/api
```

## API Integration

The frontend communicates with the Laravel backend API at `http://localhost:8000/api`.

Authentication uses Bearer tokens stored in localStorage.

See `src/js/api.js` for available endpoints.
