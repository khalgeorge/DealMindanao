<footer class="bg-gray-900 text-gray-300 mt-16">
  <div class="page-shell py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      {{-- About --}}
      <div>
        <h3 class="text-white font-bold text-lg mb-4">DealMindanao</h3>
        <p class="text-sm">Authentic products from Mindanao, connecting local artisans with the world.</p>
      </div>
      
      {{-- Quick Links --}}
      <div>
        <h4 class="text-white font-semibold mb-4">Quick Links</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="{{ route('shop') }}" class="hover:text-white transition-colors">Shop</a></li>
          <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
          <li><a href="{{ route('partner') }}" class="hover:text-white transition-colors">Become a Partner</a></li>
          <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact</a></li>
        </ul>
      </div>
      
      {{-- Customer Service --}}
      <div>
        <h4 class="text-white font-semibold mb-4">Customer Service</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="#" class="hover:text-white transition-colors">Shipping Info</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Returns</a></li>
          <li><a href="#" class="hover:text-white transition-colors">FAQs</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
        </ul>
      </div>
      
      {{-- Newsletter --}}
      <div>
        <h4 class="text-white font-semibold mb-4">Newsletter</h4>
        <p class="text-sm mb-4">Subscribe for exclusive deals and updates</p>
        <form class="flex gap-2">
          <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded bg-gray-800 border border-gray-700 text-sm focus:outline-none focus:border-brand-500">
          <button type="submit" class="btn-primary px-4 py-2 text-sm">Subscribe</button>
        </form>
      </div>
    </div>
    
    <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
      <p>&copy; {{ date('Y') }} DealMindanao. All rights reserved.</p>
    </div>
  </div>
</footer>
