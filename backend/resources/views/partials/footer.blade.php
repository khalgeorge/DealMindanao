<footer class="bg-gray-900 pt-24 pb-12 overflow-hidden relative">
  {{-- Decorative top line --}}
  <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-brand-600 via-brand-400 to-brand-600"></div>

  <div class="container mx-auto px-6 lg:px-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-24">

      {{-- Brand --}}
      <div class="space-y-8">
        <a href="{{ route('home') }}" class="inline-block">
          <img src="{{ asset('logo_main-final.png') }}" alt="DealMindanao Logo" class="h-20 w-auto">
        </a>
        <p class="text-sm text-gray-500 font-medium leading-relaxed max-w-xs">
          DealMindanao is a Mindanao-based online marketplace for hardware, food, and local products from verified regional sellers. Browse online, place your order, and pay offline via GCash or Bank Transfer after confirmation.
        </p>
        <div class="flex gap-4">
          <a href="https://www.facebook.com/dealmindanao" target="_blank" rel="noopener noreferrer" aria-label="DealMindanao on Facebook" class="w-10 h-10 bg-white/5 rounded-lg border border-white/5 flex items-center justify-center hover:bg-brand-600 transition-colors">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
        </div>
      </div>

      {{-- Marketplace --}}
      <div class="lg:pl-10">
        <h4 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-10">Marketplace</h4>
        <ul class="space-y-4">
          <li><a href="{{ route('shop') }}" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors flex items-center gap-2 group">
            <span class="w-1.5 h-1.5 bg-gray-800 rounded-full group-hover:bg-brand-600 transition-colors"></span> Shop Deals
          </a></li>
          <li><a href="{{ route('cart') }}" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors flex items-center gap-2 group">
            <span class="w-1.5 h-1.5 bg-gray-800 rounded-full group-hover:bg-brand-600 transition-colors"></span> Your Cart
          </a></li>
          <li><a href="/trust-safety" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors flex items-center gap-2 group">
            <span class="w-1.5 h-1.5 bg-gray-800 rounded-full group-hover:bg-brand-600 transition-colors"></span> Trust & Safety
          </a></li>
        </ul>
      </div>

      {{-- Company --}}
      <div class="lg:pl-10">
        <h4 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-10">Company</h4>
        <ul class="space-y-4">
          <li><a href="{{ route('about') }}" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors flex items-center gap-2 group">
            <span class="w-1.5 h-1.5 bg-gray-800 rounded-full group-hover:bg-brand-600 transition-colors"></span> About Us
          </a></li>
          <li><a href="{{ route('partner') }}" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors flex items-center gap-2 group">
            <span class="w-1.5 h-1.5 bg-gray-800 rounded-full group-hover:bg-brand-600 transition-colors"></span> Become a Partner
          </a></li>
          <li><a href="{{ route('contact') }}" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors flex items-center gap-2 group">
            <span class="w-1.5 h-1.5 bg-gray-800 rounded-full group-hover:bg-brand-600 transition-colors"></span> Contact Support
          </a></li>
          <li><a href="{{ route('help') }}" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors flex items-center gap-2 group">
            <span class="w-1.5 h-1.5 bg-gray-800 rounded-full group-hover:bg-brand-600 transition-colors"></span> Help Center
          </a></li>
        </ul>
      </div>

      {{-- Regional Focus --}}
      <div class="bg-white/5 p-10 rounded-lg border border-white/5">
        <h4 class="text-[10px] font-black text-white uppercase tracking-[0.3em] mb-6">Regional Focus</h4>
        <div class="space-y-4">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center font-black text-brand-400 text-lg italic">M</div>
            <div>
              <p class="text-xs font-black text-white uppercase tracking-tighter">Made in Mindanao</p>
              <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Direct from Source</p>
            </div>
          </div>
          <p class="text-[10px] text-gray-500 leading-relaxed font-bold uppercase tracking-wider mt-6">
            Proudly serving buyers across Davao, North &amp; South Cotabato, Bukidnon, Zamboanga, and all Mindanao regions.
          </p>
        </div>
      </div>
    </div>

    <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
      <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">&copy; {{ date('Y') }} DealMindanao. All Rights Reserved.</p>
      <div class="flex flex-wrap gap-6 justify-center md:justify-end">
        <a href="/privacy" class="text-[10px] font-bold text-gray-600 uppercase tracking-widest hover:text-white">Privacy</a>
        <a href="/terms" class="text-[10px] font-bold text-gray-600 uppercase tracking-widest hover:text-white">Terms</a>
        <a href="/refunds" class="text-[10px] font-bold text-gray-600 uppercase tracking-widest hover:text-white">Refunds</a>
        <a href="/trust-safety" class="text-[10px] font-bold text-gray-600 uppercase tracking-widest hover:text-white">Trust & Safety</a>
      </div>
    </div>
  </div>
</footer>
