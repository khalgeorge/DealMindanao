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
          A curated online marketplace for hardware and heavy equipment in Mindanao, connecting customers with quality local deals.
        </p>
        <div class="flex gap-4">
          <div class="w-10 h-10 bg-white/5 rounded-lg border border-white/5 flex items-center justify-center hover:bg-brand-600 transition-colors cursor-pointer">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
          </div>
          <div class="w-10 h-10 bg-white/5 rounded-lg border border-white/5 flex items-center justify-center hover:bg-brand-600 transition-colors cursor-pointer">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
          </div>
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
          <li><a href="{{ route('about') }}" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors">About Us</a></li>
          <li><a href="{{ route('partner') }}" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors">Become a Partner</a></li>
          <li><a href="{{ route('contact') }}" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors">Contact Support</a></li>
          <li><a href="/help" class="text-sm font-bold text-gray-500 hover:text-brand-400 transition-colors">Help Center</a></li>
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
            Supporting sustainable trade and ethical farming practices across the Davao, Bukidnon, and Zamboanga regions.
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
