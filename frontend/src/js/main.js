import '../css/app.css';
import { auth, products } from './api.js';
import { formatPrice, showToast, toggleLoadingOverlay } from './utils.js';

// Initialize app
document.addEventListener('DOMContentLoaded', async () => {
  initAuth();
  await loadFeaturedProducts();
});

// Initialize authentication state
function initAuth() {
  const userMenu = document.getElementById('user-menu');
  const guestMenu = document.getElementById('guest-menu');
  
  if (auth.isAuthenticated()) {
    userMenu?.classList.remove('hidden');
    guestMenu?.classList.add('hidden');
  } else {
    userMenu?.classList.add('hidden');
    guestMenu?.classList.remove('hidden');
  }
}

// Load featured products
async function loadFeaturedProducts() {
  try {
    const container = document.getElementById('featured-products');
    if (!container) return;

    const data = await products.getAll({ featured: true, limit: 8 });
    
    if (data.data && data.data.length > 0) {
      container.innerHTML = data.data.map(product => createProductCard(product)).join('');
    } else {
      container.innerHTML = `
        <div class="col-span-full text-center py-12">
          <p class="text-gray-500">No featured products available.</p>
        </div>
      `;
    }
  } catch (error) {
    console.error('Error loading products:', error);
    const container = document.getElementById('featured-products');
    if (container) {
      container.innerHTML = `
        <div class="col-span-full text-center py-12">
          <p class="text-red-600">Failed to load products. Please try again later.</p>
        </div>
      `;
    }
  }
}

// Create product card HTML
function createProductCard(product) {
  const hasDiscount = product.sale_price && product.sale_price < product.price;
  const discountPercent = hasDiscount 
    ? Math.round(((product.price - product.sale_price) / product.price) * 100)
    : 0;

  return `
    <a href="/product.html?id=${product.id}" class="product-card group flex flex-col">

      <!-- Image section -->
      <div class="relative h-56 bg-white p-3 overflow-hidden rounded-t-xl flex-shrink-0">
        ${product.image_url
          ? `<img src="${product.image_url}" alt="${product.name}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">`
          : `<div class="w-full h-full flex items-center justify-center text-gray-400">
              <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
              </svg>
            </div>`
        }
        ${hasDiscount ? `<span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow">${discountPercent}% OFF</span>` : ''}
      </div>

      <!-- Image / Content separator -->
      <div class="border-t border-gray-200"></div>

      <!-- Content section -->
      <div class="px-6 pt-4 pb-8 flex-1 flex flex-col gap-3">
        <h3 class="text-sm font-bold text-gray-900 line-clamp-2 leading-snug">${product.name}</h3>
        <div class="flex items-center gap-2 mt-auto">
          <span class="text-lg font-black text-brand-600">${formatPrice(hasDiscount ? product.sale_price : product.price)}</span>
          ${hasDiscount ? `<span class="text-sm text-gray-400 line-through">${formatPrice(product.price)}</span>` : ''}
        </div>
      </div>

    </a>
  `;
}
