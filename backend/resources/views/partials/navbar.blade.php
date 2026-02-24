<nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50 shadow-sm">
@php
    $navItems = \App\Models\NavigationItem::where('location', 'header')
        ->where('is_active', true)
        ->orderBy('position')
        ->get();

    $isNavActive = function (string $url): bool {
        $path = ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        return match (true) {
            $path === '' || $path === '/' => request()->routeIs('home'),
            $path === 'shop'             => request()->routeIs('shop') || request()->routeIs('product.show'),
            $path === 'cart'             => request()->routeIs('cart'),
            $path === 'about'            => request()->routeIs('about'),
            $path === 'partner'          => request()->routeIs('partner'),
            $path === 'contact'          => request()->routeIs('contact'),
            $path === 'help'             => request()->routeIs('help'),
            $path === 'account'          => request()->routeIs('account') || request()->is('account/*'),
            default                      => request()->is($path),
        };
    };

    $isCartActive    = request()->routeIs('cart');
    $isAccountActive = request()->routeIs('account') || request()->is('account/*');
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
                @php $active = $isNavActive($navItem->url); @endphp
                <a href="{{ $navItem->url }}"
                   class="text-sm transition-colors {{ $active ? 'font-semibold text-brand-600' : 'font-medium text-gray-700 hover:text-brand-600' }}"
                   @if($active) aria-current="page" @endif>
                    {{ $navItem->label }}
                </a>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="flex items-center space-x-3">
            @auth
                <a href="{{ route('account') }}"
                   class="p-2 transition-colors {{ $isAccountActive ? 'text-brand-600' : 'text-gray-700 hover:text-brand-600' }}"
                   aria-label="My Account"
                   @if($isAccountActive) aria-current="page" @endif>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="p-2 text-gray-700 hover:text-brand-600 transition-colors"
                   aria-label="Log in">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
            @endauth

            <a href="{{ route('cart') }}"
               class="relative p-2 transition-colors {{ $isCartActive ? 'text-brand-600' : 'text-gray-700 hover:text-brand-600' }}"
               aria-label="View cart"
               @if($isCartActive) aria-current="page" @endif>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span id="cart-count"
                      class="absolute -top-1 -right-1 bg-accent-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
                      aria-live="polite" aria-label="Cart items">0</span>
            </a>

            <button id="mobile-menu-btn"
                    class="md:hidden p-2 text-gray-700 hover:text-brand-600"
                    aria-label="Toggle navigation menu"
                    aria-expanded="false"
                    aria-controls="mobile-menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-2">
        @foreach($navItems as $navItem)
            @php $active = $isNavActive($navItem->url); @endphp
            <a href="{{ $navItem->url }}"
               class="block px-3 py-2 text-sm rounded-lg transition-colors {{ $active ? 'font-semibold text-brand-600 bg-brand-50' : 'font-medium text-gray-700 hover:text-brand-600 hover:bg-gray-50' }}"
               @if($active) aria-current="page" @endif>
                {{ $navItem->label }}
            </a>
        @endforeach
    </div>
</div>
</nav>

@push('scripts')
<script>
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu   = document.getElementById('mobile-menu');
    mobileMenuBtn?.addEventListener('click', () => {
        const isNowOpen = !mobileMenu.classList.toggle('hidden');
        mobileMenuBtn.setAttribute('aria-expanded', String(isNowOpen));
    });

    function updateCartCount() {
        const cart       = JSON.parse(localStorage.getItem('cart') || '[]');
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        document.getElementById('cart-count').textContent = totalItems;
    }
    updateCartCount();
    window.addEventListener('cart-updated', updateCartCount);
</script>
@endpush
