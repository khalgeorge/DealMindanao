/**
 * Global Layout Manager for DealMindanao
 * Automatically initializes Navbar, Footer, and Admin Sidebar components.
 */

import { createNavbar, initNavbar } from './components/navbar.js';
import { createFooter } from './components/footer.js';
import { createAdminSidebar, checkAuth } from './components/admin-sidebar.js';

document.addEventListener('DOMContentLoaded', () => {
  const navbarContainer = document.getElementById('navbar');
  const footerContainer = document.getElementById('footer');
  const sidebarContainer = document.getElementById('sidebar');

  // Determine page type and active page
  const path = window.location.pathname;
  const isAdminPage = path.includes('/admin/');
  const pageMap = {
    'dashboard.html': 'dashboard',
    'products.html': 'products',
    'orders.html': 'orders',
    'categories.html': 'categories',
    'companies.html': 'companies',
    'settings.html': 'settings'
  };
  
  const currentPage = Object.keys(pageMap).find(key => path.endsWith(key)) || 'home';
  const activeKey = pageMap[currentPage] || '';

  // 1. Initialize Public Layout (Navbar/Footer)
  if (navbarContainer) {
    navbarContainer.innerHTML = createNavbar();
    initNavbar();
  }

  if (footerContainer) {
    footerContainer.innerHTML = createFooter();
  }

  // 2. Initialize Admin Layout (Sidebar & Auth)
  if (isAdminPage && sidebarContainer) {
    // Check authentication first
    if (checkAuth()) {
      sidebarContainer.innerHTML = createAdminSidebar(activeKey);
    }
  }

  // 3. Global UI Accessibility Adjustments
  // Ensure images have alt tags if missing for basic a11y
  document.querySelectorAll('img').forEach(img => {
    if (!img.alt) img.alt = 'DealMindanao Asset';
  });
});
