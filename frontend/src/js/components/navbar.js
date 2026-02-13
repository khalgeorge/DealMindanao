export function createNavbar(isAdmin = false) {
  if (isAdmin) {
    return `
      <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between h-20">
            <div class="flex items-center">
              <a href="/admin/dashboard.html" class="flex items-center">
                <img src="/logo_main-final.png" alt="DealMindanao Logo" class="w-auto" style="height: calc(var(--spacing) * 20);">
              </a>
            </div>
            <div class="flex items-center space-x-4">
              <button id="logout-btn" class="btn-secondary btn-sm">Logout</button>
            </div>
          </div>
        </div>
      </nav>
    `;
  }
  
  return `
    <nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50 shadow-sm">
      <div class="page-shell">
        <div class="flex items-center justify-between h-20">
          <!-- Logo -->
          <div class="flex items-center">
            <a href="/index.html" class="flex items-center">
              <img src="/logo_main-final.png" alt="DealMindanao Logo" class="w-auto" style="height: calc(var(--spacing) * 20);">
            </a>
          </div>
          
          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-6">
            <a href="/index.html" class="text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors">Home</a>
            <a href="/shop.html" class="text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors">Shop</a>
            <a href="/about.html" class="text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors">About</a>
            <a href="/partner.html" class="text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors">Partner</a>
            <a href="/help.html" class="text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors">Help</a>
            <a href="/contact.html" class="text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors">Contact</a>
          </div>
          
          <!-- Actions -->
          <div class="flex items-center space-x-3">
            <a href="/cart.html" class="relative p-2 text-gray-700 hover:text-brand-600 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              <span id="cart-count" class="absolute -top-1 -right-1 bg-accent-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>
            </a>
            <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-700 hover:text-brand-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-2">
          <a href="/index.html" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50 rounded-lg">Home</a>
          <a href="/shop.html" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50 rounded-lg">Shop</a>
          <a href="/about.html" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50 rounded-lg">About</a>
          <a href="/partner.html" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50 rounded-lg">Partner</a>
          <a href="/help.html" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50 rounded-lg">Help</a>
          <a href="/contact.html" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50 rounded-lg">Contact</a>
        </div>
      </div>
    </nav>
  `;
}

// Initialize navbar interactivity
export function initNavbar() {
  const mobileMenuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  
  if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }
  
  // Update cart count from localStorage
  const cartCount = document.getElementById('cart-count');
  if (cartCount) {
    const updateCartCount = () => {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
      cartCount.textContent = totalItems;
    };
    
    // Initial update
    updateCartCount();
    
    // Listen for cart updates
    window.addEventListener('cart-updated', updateCartCount);
  }
  
  // Admin logout
  const logoutBtn = document.getElementById('logout-btn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
      localStorage.removeItem('token');
      window.location.href = '/admin/login.html';
    });
  }
}
