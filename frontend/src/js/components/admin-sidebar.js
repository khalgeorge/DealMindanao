// Admin sidebar component
export function createAdminSidebar(activePage) {
  return `
    <aside class="w-64 bg-white border-r border-gray-200 min-h-screen">
      <div class="border-b border-gray-200" style="padding: calc(var(--spacing) * 0.4);">
        <a href="/admin/dashboard.html" class="flex items-center justify-center">
          <img src="/logo_main-final.png" alt="DealMindanao Logo" class="w-auto" style="height: calc(var(--spacing) * 20);">
        </a>
      </div>

      <nav class="p-4 space-y-1">
        <a href="/admin/dashboard.html" class="admin-sidebar-link ${activePage === 'dashboard' ? 'active' : ''}">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
          </svg>
          Dashboard
        </a>

        <a href="/admin/products.html" class="admin-sidebar-link ${activePage === 'products' ? 'active' : ''}">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
          Products
        </a>

        <a href="/admin/orders.html" class="admin-sidebar-link ${activePage === 'orders' ? 'active' : ''}">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
          </svg>
          Orders
        </a>

        <a href="/admin/companies.html" class="admin-sidebar-link ${activePage === 'companies' ? 'active' : ''}">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
          </svg>
          Companies
        </a>

        <a href="/admin/categories.html" class="admin-sidebar-link ${activePage === 'categories' ? 'active' : ''}">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
          Categories
        </a>

        <div class="pt-4 mt-4 border-t border-gray-100">
          <a href="/admin/settings.html" class="admin-sidebar-link ${activePage === 'settings' ? 'active' : ''}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Settings
          </a>
          
          <button id="logout-btn" class="admin-sidebar-link w-full text-left">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Logout
          </button>
        </div>
      </nav>
    </aside>
  `;
}

// Check authentication
export function checkAuth() {
  // DEVELOPMENT MODE: Disable auth check for frontend development
  // TODO: Re-enable this when integrating with backend API
  return true;
  
  /* PRODUCTION CODE - Uncomment when ready to integrate:
  const token = localStorage.getItem('auth_token');
  const userStr = localStorage.getItem('user');
  
  if (!token || !userStr) {
    window.location.href = '/admin/login.html';
    return false;
  }
  
  try {
    const user = JSON.parse(userStr);
    
    // Check if user is admin
    if (!user.is_admin) {
      console.error('Access denied: User is not an admin');
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user');
      window.location.href = '/admin/login.html';
      return false;
    }
    
    return true;
  } catch (error) {
    console.error('Auth check error:', error);
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    window.location.href = '/admin/login.html';
    return false;
  }
  */
}

// Initialize logout button
export function initLogout() {
  const logoutBtn = document.getElementById('logout-btn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', async (e) => {
      e.preventDefault();
      
      try {
        // Import auth API
        const { auth } = await import('/src/js/api.js');
        
        // Call logout endpoint
        await auth.logout();
      } catch (error) {
        console.error('Logout API error:', error);
      } finally {
        // Clear local storage and redirect regardless of API call result
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        window.location.href = '/admin/login.html';
      }
    });
  }
}
