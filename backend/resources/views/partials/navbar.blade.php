<nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50 shadow-sm">
@php
    $navItems = \App\Models\NavigationItem::where('location', 'header')
        ->where('is_active', true)
        ->orderBy('position')
        ->get();
@endphp
<div class="page-shell">
    <div class="flex items-center justify-between h-20">
      {{-- Logo --}}
      <div class="flex items-center">
        <a href="{{ route('home') }}" class="flex items-center">
          <img src="{{ asset('logo_main-final.png') }}" alt="DealMindanao Logo" class="w-auto" style="height: calc(var(--spacing) * 20);">
        </a>
      </div>
      
      {{-- Desktop Navigation --}}
      <div class="hidden md:flex items-center space-x-6">
        @foreach($navItems as $navItem)
          <a href="{{ $navItem->url }}" class="text-sm font-medium text-gray-700 hover:text-brand-600 transition-colors {{ request()->is(ltrim($navItem->url, '/')) || request()->fullUrlIs(url($navItem->url)) ? 'text-brand-600' : '' }}">
            {{ $navItem->label }}
          </a>
        @endforeach
      </div>
      
      {{-- Actions --}}
      <div class="flex items-center space-x-3">
        @auth
          <a href="{{ route('account') }}" class="p-2 text-gray-700 hover:text-brand-600 transition-colors" title="My Account">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </a>
        @else
          <a href="{{ route('login') }}" class="p-2 text-gray-700 hover:text-brand-600 transition-colors" title="Login">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </a>
        @endauth
        
        <a href="{{ route('cart') }}" class="relative p-2 text-gray-700 hover:text-brand-600 transition-colors">
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
    
    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-2">
      @foreach($navItems as $navItem)
        <a href="{{ $navItem->url }}" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50 rounded-lg">
          {{ $navItem->label }}
        </a>
      @endforeach
  </div>
</nav>

@push('scripts')
<script>
  // Mobile menu toggle
  document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
    document.getElementById('mobile-menu').classList.toggle('hidden');
  });
  
  // Update cart count from localStorage
  function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('cart-count').textContent = totalItems;
  }
  
  updateCartCount();
  window.addEventListener('cart-updated', updateCartCount);
</script>
@endpush
