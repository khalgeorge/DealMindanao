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
    <a href="/product.html?id=${product.id}" class="card group hover:shadow-lg transition-shadow">
      <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-4 relative">
        ${product.image_url 
          ? `<img src="${product.image_url}" alt="${product.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">`
          : `<div class="w-full h-full flex items-center justify-center text-gray-400">
              <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
              </svg>
            </div>`
        }
        ${hasDiscount ? `<span class="absolute top-2 right-2 badge-danger">${discountPercent}% OFF</span>` : ''}
      </div>
      <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">${product.name}</h3>
      <div class="flex items-center gap-2">
        <span class="text-lg font-bold text-brand-600">${formatPrice(hasDiscount ? product.sale_price : product.price)}</span>
        ${hasDiscount ? `<span class="text-sm text-gray-500 line-through">${formatPrice(product.price)}</span>` : ''}
      </div>
    </a>
  `;
}
