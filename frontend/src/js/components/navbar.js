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

  // ── Active-state helpers ─────────────────────────────────────────────────
  // Normalise the path: strip trailing slash, treat /index.html as /
  const raw = window.location.pathname.replace(/\/$/, '') || '/';
  const path = raw === '/index.html' ? '/' : raw;

  /**
   * Returns true when `path` matches any of the given patterns.
   * A pattern ending with /* matches the prefix (e.g. /product/* matches
   * /product/any-slug). Exact patterns are compared literally.
   * The most-specific rule wins because patterns are tested in order and
   * each nav item owns its own non-overlapping pattern set.
   */
  function isActive(patterns) {
    return patterns.some(p => {
      if (p.endsWith('/*')) {
        return path.startsWith(p.slice(0, -1)); // strip the *
      }
      return path === p;
    });
  }

  // Desktop nav link — aria-current drives color via CSS rule in app.css
  function dLink(href, label, patterns) {
    const active = isActive(patterns);
    const cls = active
      ? 'text-sm font-medium transition-colors'
      : 'text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors';
    const aria = active ? ' aria-current="page"' : '';
    return `<a href="${href}" class="${cls}"${aria}>${label}</a>`;
  }

  // Mobile nav link — aria-current drives color+bg via CSS rule in app.css
  function mLink(href, label, patterns) {
    const active = isActive(patterns);
    const cls = active
      ? 'block px-3 py-2 text-sm font-medium rounded-lg'
      : 'block px-3 py-2 text-sm font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50 rounded-lg';
    const aria = active ? ' aria-current="page"' : '';
    return `<a href="${href}" class="${cls}"${aria}>${label}</a>`;
  }

  // Cart icon: active when on /cart.html
  const cartActive = isActive(['/cart.html']);
  const cartIconCls = cartActive
    ? 'relative p-2 transition-colors'
    : 'relative p-2 text-gray-700 hover:text-brand-600 transition-colors';
  const cartAria = cartActive ? ' aria-current="page"' : '';

  // Account icon: active on /account.html or any /account/* path
  const accountActive = isActive(['/account.html', '/account/*']);
  const accountIconCls = accountActive
    ? 'hidden p-2 transition-colors'
    : 'hidden p-2 text-gray-700 hover:text-brand-600 transition-colors';
  const accountAria = accountActive ? ' aria-current="page"' : '';
  // ────────────────────────────────────────────────────────────────────────

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
            ${dLink('/index.html', 'Home',    ['/'])}
            ${dLink('/shop.html',  'Shop',    ['/shop.html', '/product/*'])}
            ${dLink('/about.html', 'About',   ['/about.html'])}
            ${dLink('/partner.html','Partner',['/partner.html'])}
            ${dLink('/help.html',  'Help',    ['/help.html'])}
            ${dLink('/contact.html','Contact',['/contact.html'])}
          </div>
          
          <!-- Actions -->
          <div class="flex items-center space-x-3">
            <a href="#" id="user-account-link" class="${accountIconCls}"${accountAria}>
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </a>
            <a href="/cart.html" class="${cartIconCls}"${cartAria}>
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
          ${mLink('/index.html', 'Home',    ['/'])}
          ${mLink('/shop.html',  'Shop',    ['/shop.html', '/product/*'])}
          ${mLink('/about.html', 'About',   ['/about.html'])}
          ${mLink('/partner.html','Partner',['/partner.html'])}
          ${mLink('/help.html',  'Help',    ['/help.html'])}
          ${mLink('/contact.html','Contact',['/contact.html'])}
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

  // User account link
  const userAccountLink = document.getElementById('user-account-link');
  if (userAccountLink) {
    const authToken = localStorage.getItem('auth_token');
    if (authToken) {
      userAccountLink.href = '/account.html';
      userAccountLink.classList.remove('hidden');
    } else {
      userAccountLink.href = '/login.html';
      userAccountLink.classList.remove('hidden');
    }
  }
}
