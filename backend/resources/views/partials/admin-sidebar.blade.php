@php
    $isPageEditor = request()->routeIs(
        'admin.home_page.*', 'admin.about_page.*', 'admin.partner_page.*',
        'admin.contact_page.*', 'admin.help_page.*', 'admin.trust_safety.*',
        'admin.privacy_page.*', 'admin.refund_policy.*', 'admin.terms_page.*'
    );
    $isSettingsOpen = request()->routeIs('admin.settings.*') || $isPageEditor;
@endphp

<aside class="admin-sidebar overflow-y-auto">
  <div class="border-b border-white/10" style="padding: calc(var(--spacing) * 0.4);">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center">
      <img src="{{ asset('logo_main-final.png') }}" alt="DealMindanao Logo" class="w-auto" style="height: calc(var(--spacing) * 20);">
    </a>
  </div>

  <nav class="p-4 space-y-1">

    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
      </svg>
      Dashboard
    </a>

    {{-- Products --}}
    <a href="{{ route('admin.products.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
      <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
      </svg>
      Products
    </a>

    {{-- Orders --}}
    <a href="{{ route('admin.orders.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
      <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
      </svg>
      Orders
    </a>

    {{-- Partners (supplier sellers) --}}
    <a href="{{ route('admin.suppliers.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
      <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
      </svg>
      Partners
    </a>

    {{-- Brands --}}
    <a href="{{ route('admin.brands.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
      <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
      </svg>
      Brands
    </a>

    {{-- Categories --}}
    <a href="{{ route('admin.categories.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
      <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
      </svg>
      Categories
    </a>

    {{-- Navigation --}}
    <a href="{{ route('admin.navigation.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.navigation.*') ? 'active' : '' }}">
      <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
      Navigation
    </a>

    {{-- Reviews --}}
    <a href="{{ route('admin.reviews.index') }}" class="admin-sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
      <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
      </svg>
      Reviews
      @php $pendingReviews = \App\Models\Review::pending()->count(); @endphp
      @if($pendingReviews > 0)
      <span class="ml-auto px-1.5 py-0.5 rounded-full bg-amber-400 text-gray-900 text-[9px] font-black">{{ $pendingReviews }}</span>
      @endif
    </a>

    <div class="pt-4 mt-4 border-t border-white/10 space-y-1">

      {{-- ══════════════════════════════════════ --}}
      {{-- SETTINGS — collapsible parent          --}}
      {{-- ══════════════════════════════════════ --}}
      <div>
        <button
          id="settings-toggle"
          type="button"
          onclick="toggleSubmenu('settings')"
          class="admin-sidebar-link w-full {{ $isSettingsOpen ? 'active' : '' }}"
          aria-expanded="{{ $isSettingsOpen ? 'true' : 'false' }}"
        >
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          Settings
          <svg id="settings-chevron" class="w-4 h-4 ml-auto flex-shrink-0 transition-transform duration-200 {{ $isSettingsOpen ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>

        {{-- Settings submenu --}}
        <div id="settings-submenu" class="mt-1 space-y-0.5 {{ $isSettingsOpen ? '' : 'hidden' }}">

          @php
            $settingTab = request()->query('tab', 'general');
            $onSettings  = request()->routeIs('admin.settings.*');
          @endphp

          {{-- General --}}
          <a href="{{ route('admin.settings.index') }}?tab=general"
             class="flex items-center pl-11 pr-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150 {{ ($onSettings && in_array($settingTab, ['general', null, ''])) ? 'text-white bg-white/20 font-semibold' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}">
            General
          </a>

          {{-- Regional & Logistics --}}
          <a href="{{ route('admin.settings.index') }}?tab=regional"
             class="flex items-center pl-11 pr-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150 {{ ($onSettings && $settingTab === 'regional') ? 'text-white bg-white/20 font-semibold' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}">
            Regional &amp; Logistics
          </a>

          {{-- Security & Access --}}
          <a href="{{ route('admin.settings.index') }}?tab=security"
             class="flex items-center pl-11 pr-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150 {{ ($onSettings && $settingTab === 'security') ? 'text-white bg-white/20 font-semibold' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}">
            Security &amp; Access
          </a>

          {{-- SMS & Email Alerts --}}
          <a href="{{ route('admin.settings.index') }}?tab=notifications"
             class="flex items-center pl-11 pr-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150 {{ ($onSettings && $settingTab === 'notifications') ? 'text-white bg-white/20 font-semibold' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}">
            SMS &amp; Email Alerts
          </a>

          {{-- ── PAGE EDITORS — nested collapsible ── --}}
          <div>
            <button
              id="page-editors-toggle"
              type="button"
              onclick="toggleSubmenu('page-editors')"
              class="flex items-center w-full pl-11 pr-3 py-2 text-sm font-medium rounded-lg transition-colors duration-150 {{ $isPageEditor ? 'text-white bg-white/20 font-semibold' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}"
              aria-expanded="{{ $isPageEditor ? 'true' : 'false' }}"
            >
              <svg class="w-3.5 h-3.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Page Editors
              <svg id="page-editors-chevron" class="w-3.5 h-3.5 ml-auto flex-shrink-0 transition-transform duration-200 {{ $isPageEditor ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>

            {{-- Page Editors sub-submenu --}}
            <div id="page-editors-submenu" class="mt-1 {{ $isPageEditor ? '' : 'hidden' }}">
              @php
                $pageEditorLinks = [
                  [
                    'label'   => 'Home Page',
                    'route'   => 'admin.home_page.index',
                    'pattern' => 'admin.home_page.*',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                  ],
                  [
                    'label'   => 'About Page',
                    'route'   => 'admin.about_page.index',
                    'pattern' => 'admin.about_page.*',
                    'icon'    => '<circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-4m0-4h.01"/>',
                  ],
                  [
                    'label'   => 'Partner Page',
                    'route'   => 'admin.partner_page.index',
                    'pattern' => 'admin.partner_page.*',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
                  ],
                  [
                    'label'   => 'Contact Page',
                    'route'   => 'admin.contact_page.index',
                    'pattern' => 'admin.contact_page.*',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                  ],
                  [
                    'label'   => 'Help / FAQ',
                    'route'   => 'admin.help_page.index',
                    'pattern' => 'admin.help_page.*',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                  ],
                  [
                    'label'   => 'Trust & Safety',
                    'route'   => 'admin.trust_safety.index',
                    'pattern' => 'admin.trust_safety.*',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                  ],
                  [
                    'label'   => 'Privacy Policy',
                    'route'   => 'admin.privacy_page.index',
                    'pattern' => 'admin.privacy_page.*',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>',
                  ],
                  [
                    'label'   => 'Refund Policy',
                    'route'   => 'admin.refund_policy.index',
                    'pattern' => 'admin.refund_policy.*',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
                  ],
                  [
                    'label'   => 'Terms of Service',
                    'route'   => 'admin.terms_page.index',
                    'pattern' => 'admin.terms_page.*',
                    'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                  ],
                ];
              @endphp
              {{-- Guide rail: ml-11 aligns the border with the Page Editors icon position --}}
              <div class="ml-11 pl-3 border-l-2 border-white/20 space-y-0.5 py-0.5">
                @foreach ($pageEditorLinks as $pe)
                @php $peActive = request()->routeIs($pe['pattern']); @endphp
                <a href="{{ route($pe['route']) }}"
                   class="group relative flex items-center gap-2.5 pl-3 pr-3 py-1.5 text-xs font-medium rounded-lg transition-colors duration-150 {{ $peActive ? 'bg-white/20 text-white font-semibold' : 'text-emerald-200 hover:bg-white/10 hover:text-white' }}">
                  {{-- Active left border indicator — sits exactly on the guide rail --}}
                  @if($peActive)
                  <span class="absolute -left-[3px] inset-y-1.5 w-0.5 rounded-full bg-white/70"></span>
                  @endif
                  <svg class="w-3.5 h-3.5 flex-shrink-0 {{ $peActive ? 'text-white' : 'text-emerald-300 group-hover:text-white' }}"
                       fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $pe['icon'] !!}
                  </svg>
                  <span class="truncate">{{ $pe['label'] }}</span>
                </a>
                @endforeach
              </div>
            </div>
          </div>

        </div>
      </div>

      {{-- Logout --}}
      <form action="{{ route('admin.logout') }}" method="POST">
        @csrf
        <button type="submit" class="admin-sidebar-link w-full text-left">
          <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          Logout
        </button>
      </form>

    </div>
  </nav>
</aside>

<script>
function toggleSubmenu(name) {
  var submenu = document.getElementById(name + '-submenu');
  var toggle  = document.getElementById(name + '-toggle');
  var chevron = document.getElementById(name + '-chevron');
  var isHidden = submenu.classList.contains('hidden');
  submenu.classList.toggle('hidden');
  if (toggle)  toggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
  if (chevron) chevron.classList.toggle('rotate-180');
}
</script>
