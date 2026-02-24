@extends('layouts.app')

@section('meta_title', 'Your Cart | DealMindanao')
@section('meta_description', 'No payment is required at checkout. Our team will contact you to confirm your order, payment method, and delivery details.')
@section('meta_keywords', 'Mindanao online shopping cart, DealMindanao cart, Mindanao marketplace checkout, local sellers Mindanao cart')
@section('meta_robots', 'noindex, nofollow')
@section('canonical', 'https://dealmindanao.com/cart')

@section('content')
<div class="page-shell py-12">

  {{-- Stepper --}}
  <div class="flex items-center justify-center mb-12">
    <div class="flex items-center gap-4 text-sm font-bold">
      <div class="flex items-center gap-2 text-brand-600">
        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center">1</span>
        <span>Cart</span>
      </div>
      <div class="w-12 h-px bg-gray-300"></div>
      <div class="flex items-center gap-2 text-gray-400">
        <span class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">2</span>
        <span class="text-gray-500">Checkout</span>
      </div>
    </div>
  </div>

  <div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">

      {{-- Cart Items Column --}}
      <div class="lg:col-span-2 space-y-6">
        <div class="flex items-center justify-between mb-2">
          <h1 class="text-3xl font-black text-gray-900">Your Cart – Review Your Mindanao Orders</h1>
          <p class="sr-only">Review your selected products from verified Mindanao sellers before checkout. No online payment required — our team will contact you to confirm your order, delivery, and payment method.</p>
          <span class="text-sm font-bold text-gray-400 uppercase tracking-widest"><span id="items-count-badge">0</span> Items</span>
        </div>

        <div id="cart-list" class="space-y-4">
          {{-- Dynamic items injected via JS --}}
        </div>

        {{-- Empty State --}}
        <div id="cart-empty" class="hidden text-center py-20 bg-white rounded-lg border-2 border-dashed border-gray-200">
          <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
          </div>
          <h2 class="text-xl font-bold text-gray-900">Your cart is empty</h2>
          <p class="text-gray-500 mt-2 mb-8">Explore verified Mindanao deals and add items to get started.</p>
          <a href="/shop" class="btn-primary">Explore Local Deals in Mindanao</a>
        </div>
      </div>

      {{-- Summary Column --}}
      <div id="cart-summary">
        <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-100 sticky top-24">
          <h3 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h3>

          <div class="space-y-4 mb-8">
            <div class="flex justify-between text-gray-600">
              <span>Subtotal</span>
              <span id="subtotal" class="font-bold text-gray-900">₱0.00</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Mindanao Delivery</span>
              <span id="shipping-fee" class="font-bold text-green-600">FREE</span>
            </div>
            <div class="border-t border-gray-100 pt-4 flex justify-between">
              <span class="text-lg font-bold text-gray-900">Total</span>
              <span id="total" class="text-2xl font-black text-brand-600">₱0.00</span>
            </div>
          </div>

          <button id="checkout-btn" onclick="window.location.href='/checkout'" class="btn-primary btn-lg w-full shadow-lg shadow-brand-200/50 group">
            Proceed to Checkout
            <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
          </button>

          <div class="mt-6 flex flex-col gap-4">
            <div class="flex items-center justify-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
              Order Online · Pay Offline After Confirmation by Our Team
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('styles')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://dealmindanao.com"
    },
    {
      "@@type": "ListItem",
      "position": 2,
      "name": "Cart",
      "item": "https://dealmindanao.com/cart"
    }
  ]
}
</script>
<style>
  /* Scoped: hide summary on mobile by default; always show on desktop */
  #cart-summary { display: none; }
  @media (min-width: 1024px) { #cart-summary { display: block; } }
</style>
@endpush

@push('scripts')
<script>
  function formatPrice(amount) {
    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
  }

  function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `px-6 py-4 rounded-lg shadow-lg text-white text-sm font-medium ${type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-gray-700'}`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
  }

  function loadCart() {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');

    // Consolidate duplicate items by merging quantities
    const consolidatedCart = [];
    cart.forEach(item => {
      const existing = consolidatedCart.find(c => c.id == item.id);
      if (existing) {
        existing.quantity += item.quantity;
      } else {
        consolidatedCart.push({ ...item });
      }
    });

    // Save consolidated cart back to localStorage
    if (consolidatedCart.length !== cart.length) {
      localStorage.setItem('cart', JSON.stringify(consolidatedCart));
      window.dispatchEvent(new Event('cart-updated'));
    }

    cart = consolidatedCart;

    const list = document.getElementById('cart-list');
    const empty = document.getElementById('cart-empty');
    const summary = document.getElementById('cart-summary');
    const itemsCountBadge = document.getElementById('items-count-badge');

    itemsCountBadge.textContent = cart.length;

    if (cart.length === 0) {
      list.innerHTML = '';
      empty.classList.remove('hidden');
      summary.style.display = ''; // scoped CSS: none on mobile, block on desktop
      document.getElementById('subtotal').textContent = formatPrice(0);
      document.getElementById('total').textContent = formatPrice(0);
      document.getElementById('checkout-btn').classList.add('hidden');
      return;
    }

    empty.classList.add('hidden');
    summary.style.display = 'block'; // show on all breakpoints when cart has items
    document.getElementById('checkout-btn').classList.remove('hidden');

    let subtotal = 0;
    list.innerHTML = cart.map((item, idx) => {
      const itemPrice = item.price * (1 - (item.discount_percentage || 0) / 100);
      const itemTotal = itemPrice * item.quantity;
      subtotal += itemTotal;

      const defaultImage = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=150';
      const productImage = (item.images && item.images.length > 0) ? item.images[0] : defaultImage;

      return `
        <div class="bg-white p-4 sm:p-6 rounded-lg border border-gray-100 flex gap-4 sm:gap-6 hover:shadow-md transition-shadow">
          <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg overflow-hidden bg-gray-50 shrink-0">
            <img src="${productImage}" alt="${item.name} from Mindanao seller ${item.company || 'Local Partner'}" class="w-full h-full object-cover">
          </div>
          <div class="flex-1 flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start gap-2">
                <div>
                  <h3 class="font-bold text-gray-900 text-sm sm:text-lg mb-1">${item.name}</h3>
                  <p class="text-xs text-gray-500">${item.company || 'Mindanao Partner'}</p>
                </div>
                <button onclick="removeFromCart(${idx})" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
              </div>
            </div>
            <div class="flex justify-between items-end mt-4">
              <div class="flex items-center bg-gray-100 rounded-lg p-1">
                <button onclick="updateQty(${idx}, -1)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-brand-600 transition-colors">-</button>
                <span class="w-8 text-center font-bold text-sm">${item.quantity}</span>
                <button onclick="updateQty(${idx}, 1)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-brand-600 transition-colors">+</button>
              </div>
              <div class="text-right">
                <p class="text-[10px] text-gray-400 font-bold uppercase">Item Total</p>
                <span class="font-black text-brand-600 text-lg">${formatPrice(itemTotal)}</span>
              </div>
            </div>
          </div>
        </div>
      `;
    }).join('');

    document.getElementById('subtotal').textContent = formatPrice(subtotal);
    document.getElementById('total').textContent = formatPrice(subtotal);
  }

  window.removeFromCart = (idx) => {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const removedItem = cart[idx];
    cart.splice(idx, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
    showToast(`${removedItem.name} removed from cart`, 'info');
    window.dispatchEvent(new Event('cart-updated'));
  };

  window.updateQty = (idx, delta) => {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const newQty = Math.max(1, cart[idx].quantity + delta);
    if (newQty > 99) return;
    cart[idx].quantity = newQty;
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
  };

  // ─── Reorder flash: merge server-side items into localStorage cart ──────────
  @if(session('reorder_items'))
  (function () {
    const reorderItems = @json(session('reorder_items'));
    const existing = JSON.parse(localStorage.getItem('cart') || '[]');
    reorderItems.forEach(function (newItem) {
      const found = existing.find(function (c) { return c.id == newItem.id; });
      if (found) {
        found.quantity += newItem.quantity;
      } else {
        existing.push(newItem);
      }
    });
    localStorage.setItem('cart', JSON.stringify(existing));
    window.dispatchEvent(new Event('cart-updated'));
  })();
  @endif

  loadCart();

  @if(session('reorder_skipped'))
  showToast('Some items could not be added because they\'re currently unavailable.', 'info');
  @endif
</script>
@endpush