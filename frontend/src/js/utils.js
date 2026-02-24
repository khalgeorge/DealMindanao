// Utility functions for the frontend
import { api } from './api.js';

/**
 * Format price to Philippine Peso
 */
export function formatPrice(amount) {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
  }).format(amount);
}

/**
 * Format date to localized string
 */
export function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-PH', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
}

/**
 * Calculate discount percentage
 */
export function calculateDiscount(originalPrice, salePrice) {
  if (!originalPrice || !salePrice) return 0;
  return Math.round(((originalPrice - salePrice) / originalPrice) * 100);
}

/**
 * Debounce function for search inputs
 */
export function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

/**
 * Show/hide loading spinner
 */
export function toggleLoadingOverlay(show = true) {
  const overlay = document.getElementById('loading-overlay');
  if (overlay) {
    overlay.classList.toggle('hidden', !show);
  }
}

/**
 * Show toast notification
 */
export function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = `fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white z-50 
    ${type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600'}`;
  toast.textContent = message;
  
  document.body.appendChild(toast);
  
  setTimeout(() => {
    toast.remove();
  }, 3000);
}

/**
 * Validate email format
 */
export function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

/**
 * Get query parameter from URL
 */
export function getQueryParam(param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
}

/**
 * Update URL query parameters without reload
 */
export function updateQueryParams(params) {
  const url = new URL(window.location);
  Object.entries(params).forEach(([key, value]) => {
    if (value) {
      url.searchParams.set(key, value);
    } else {
      url.searchParams.delete(key);
    }
  });
  window.history.pushState({}, '', url);
}

/**
 * Fetch /api/system/info and render the environment banner.
 * Requires a <div id="env-banner"> element in the page.
 * Call once per admin page after auth is verified.
 */
export async function initEnvBanner() {
  const banner = document.getElementById('env-banner');
  if (!banner) return;
  try {
    const resp = await api.get('/system/info');
    if (resp.data.is_production) {
      banner.innerHTML = `
        <div class="flex items-center gap-3 px-5 py-3.5 bg-red-50 border border-red-200 rounded-lg">
          <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
          </svg>
          <div>
            <p class="text-sm font-black text-red-800 uppercase tracking-widest">Production Mode &mdash; Demo data disabled</p>
            <p class="text-xs text-red-600 mt-0.5">You are in <strong>production</strong>. Seeders and factories are blocked. All data is real and customer-facing.</p>
          </div>
        </div>`;
    } else {
      banner.innerHTML = `
        <div class="flex items-center gap-3 px-5 py-3.5 bg-amber-50 border border-amber-200 rounded-lg">
          <svg class="w-5 h-5 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <div>
            <p class="text-sm font-black text-amber-800 uppercase tracking-widest">Development Mode &mdash; ${resp.data.environment}</p>
            <p class="text-xs text-amber-600 mt-0.5">You are in the <strong>${resp.data.environment}</strong> environment. Demo seeders may be active.</p>
          </div>
        </div>`;
    }
    banner.classList.remove('hidden');
  } catch {
    // Banner is non-critical — fail silently if endpoint unavailable
  }
}
