@extends('layouts.app')

@section('meta_title', 'Checkout | Submit Your Order – DealMindanao')
@section('meta_description', 'Confirm your details to place an offline payment request.')

@section('content')
<div class="page-shell py-12">

  {{-- Stepper --}}
  <div class="flex items-center justify-center mb-12">
    <div class="flex items-center gap-4 text-sm font-bold">
      <div class="flex items-center gap-2 text-green-600">
        <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">✓</span>
        <span>Cart</span>
      </div>
      <div class="w-12 h-px bg-green-600"></div>
      <div class="flex items-center gap-2 text-brand-600">
        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center">2</span>
        <span>Checkout</span>
      </div>
    </div>
  </div>

  <div class="max-w-6xl mx-auto">

    <form id="checkout-form" class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start" method="POST" action="{{ route('checkout.store') }}">
      @csrf

      {{-- Hidden fields populated by JS — display:none removes from grid flow --}}
      <div id="cart-hidden-fields" class="hidden"></div>
      <input type="hidden" name="shipping_city" id="field-city">
      <input type="hidden" name="shipping_province" id="field-province">

      {{-- Main Form Column --}}
      <div class="lg:col-span-2 space-y-8">

        @if($errors->any())
          <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="space-y-1 text-sm text-red-700">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div>
          <h1 class="text-3xl font-black text-gray-900 mb-2">Checkout – Order Request</h1>
          <p class="text-gray-500">Please provide accurate contact details so we can confirm your order faster.</p>
        </div>

        {{-- Delivery Information Card --}}
        <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm">
          <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 text-sm">1</span>
            Delivery Information
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
              <input type="text" name="shipping_name" required placeholder="Juan Dela Cruz" class="input" value="{{ old('shipping_name') }}">
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Phone Number</label>
              <input type="tel" name="shipping_phone" required placeholder="09XX XXX XXXX" class="input" value="{{ old('shipping_phone') }}">
            </div>
            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
              <input type="email" name="shipping_email" class="input bg-gray-50 cursor-not-allowed" value="{{ auth()->user()->email }}" readonly>
              <p class="mt-2 text-xs text-gray-400">Order updates will be sent to this address.</p>
            </div>
            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Complete Address</label>
              <textarea name="shipping_address" required rows="3" placeholder="House #, Street, Barangay, City/Municipality, Province" class="input">{{ old('shipping_address') }}</textarea>
              <p class="mt-2 text-xs text-brand-600 font-medium">Currently serving all Mindanao regions.</p>
            </div>
          </div>
        </div>

        {{-- Payment Preference Card --}}
        <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm">
          <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 text-sm">2</span>
            Payment Preference
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all border-brand-600 bg-brand-50" id="label-cod">
              <input type="radio" name="payment_method" value="cod" checked class="hidden">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-brand-600 font-bold">₱</div>
                <div>
                  <p class="font-bold text-gray-900">Cash on Delivery</p>
                  <p class="text-xs text-gray-500">Pay when you receive items</p>
                </div>
              </div>
              <div class="ml-auto w-5 h-5 border-4 border-brand-600 rounded-full bg-white" id="radio-dot-cod"></div>
            </label>

            <label class="relative flex items-center p-4 border-2 border-gray-100 rounded-lg cursor-pointer hover:border-brand-200 transition-all" id="label-gcash">
              <input type="radio" name="payment_method" value="gcash" class="hidden">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold">G</div>
                <div>
                  <p class="font-bold text-gray-900">GCash / Bank</p>
                  <p class="text-xs text-gray-500">Direct wallet transfer</p>
                </div>
              </div>
              <div class="ml-auto w-5 h-5 border-2 border-gray-200 rounded-full bg-white" id="radio-dot-gcash"></div>
            </label>
          </div>

          {{-- Offline Payment Disclaimer --}}
          <div class="mt-8 p-6 bg-amber-50 rounded-lg border-2 border-amber-200 flex gap-4">
            <div class="shrink-0 text-amber-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
              <p class="text-sm font-bold text-amber-900 mb-1">Important Notice</p>
              <p class="text-sm text-amber-800 leading-relaxed mb-2">This is an order request only. No online payment is required. Our team will contact you within 24 hours to confirm payment and delivery.</p>
              <div class="flex gap-3 text-xs">
                <a href="/terms" class="text-amber-900 hover:underline font-semibold">Terms of Service →</a>
                <a href="/refunds" class="text-amber-900 hover:underline font-semibold">Refund Policy →</a>
              </div>
            </div>
          </div>
        </div>

        {{-- Privacy Consent Card --}}
        <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm">
          <label class="flex items-start gap-3 cursor-pointer group">
            <input type="checkbox" name="privacy_consent" required class="mt-1 w-5 h-5 text-brand-600 rounded border-gray-300 focus:ring-brand-500">
            <span class="text-sm text-gray-700 leading-relaxed">
              I agree to the collection and use of my personal information for order processing and delivery coordination in accordance with the <a href="/privacy" class="text-brand-600 hover:underline font-semibold">Privacy Policy</a>.
            </span>
          </label>
        </div>
      </div>

      {{-- Order Summary Sidebar --}}
      <div class="lg:col-span-1">
        <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-100 sticky top-24">
          <h3 class="text-xl font-bold text-gray-900 mb-6 uppercase tracking-wider text-center">Your Order</h3>

          <div id="checkout-items" class="space-y-4 mb-8 max-h-60 overflow-y-auto pr-2">
            {{-- Summary items populated by JS --}}
          </div>

          <div class="space-y-3 mb-8 pt-6 border-t border-gray-100">
            <div class="flex justify-between text-gray-500 text-sm">
              <span>Subtotal</span>
              <span id="subtotal" class="font-bold text-gray-900">₱0.00</span>
            </div>
            <div class="flex justify-between text-gray-500 text-sm">
              <span>Shipping</span>
              <span class="font-bold text-green-600">FREE</span>
            </div>
            <div class="flex justify-between items-end pt-2">
              <span class="text-lg font-bold text-gray-900 leading-none">Total</span>
              <span id="total" class="text-3xl font-black text-brand-600 leading-none">₱0.00</span>
            </div>
          </div>

          <button type="submit" class="btn-primary btn-lg w-full shadow-xl shadow-brand-200/50 flex flex-col items-center gap-0 py-4 group">
            <span class="text-lg font-black tracking-tight">Submit Order Request</span>
            <span class="text-[10px] opacity-70 font-bold uppercase tracking-widest flex items-center gap-1 group-hover:translate-x-1 transition-transform">
              Process Regionally
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </span>
          </button>

          <p class="mt-6 text-[10px] text-center text-gray-400 font-bold uppercase tracking-widest">
            No automatic charges. Pay later.
          </p>
        </div>
      </div>

    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function formatPrice(amount) {
    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
  }

  function loadCheckoutSummary() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    if (cart.length === 0) {
      window.location.href = '/cart';
      return;
    }

    // Populate hidden cart fields for form submission
    const hiddenContainer = document.getElementById('cart-hidden-fields');
    hiddenContainer.innerHTML = cart.map((item, i) => `
      <input type="hidden" name="items[${i}][product_id]" value="${item.id}">
      <input type="hidden" name="items[${i}][quantity]" value="${item.quantity}">
      <input type="hidden" name="items[${i}][price]" value="${item.price}">
    `).join('');

    // Populate order summary
    const list = document.getElementById('checkout-items');
    let subtotal = 0;

    list.innerHTML = cart.map(item => {
      const itemPrice = item.price * (1 - (item.discount_percentage || 0) / 100);
      const itemTotal = itemPrice * item.quantity;
      subtotal += itemTotal;

      const defaultImage = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=150';
      const productImage = (item.images && item.images.length > 0) ? item.images[0] : defaultImage;

      return `
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-lg bg-gray-50 overflow-hidden shrink-0 border border-gray-100">
            <img src="${productImage}" alt="${item.name}" class="w-full h-full object-cover">
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-gray-900 truncate">${item.name}</p>
            <p class="text-xs text-gray-500">${item.quantity} × ${formatPrice(itemPrice)}</p>
          </div>
          <div class="text-right">
            <p class="text-sm font-bold text-brand-600">${formatPrice(itemTotal)}</p>
          </div>
        </div>
      `;
    }).join('');

    document.getElementById('subtotal').textContent = formatPrice(subtotal);
    document.getElementById('total').textContent = formatPrice(subtotal);
  }

  function updatePaymentUI(method) {
    const codLabel   = document.getElementById('label-cod');
    const gcashLabel = document.getElementById('label-gcash');
    const codDot     = document.getElementById('radio-dot-cod');
    const gcashDot   = document.getElementById('radio-dot-gcash');

    if (method === 'cod') {
      // Activate COD label
      codLabel.classList.add('border-brand-600', 'bg-brand-50');
      codLabel.classList.remove('border-gray-100');
      // Activate COD dot (filled)
      codDot.classList.remove('border-2', 'border-gray-200');
      codDot.classList.add('border-4', 'border-brand-600');
      // Deactivate GCash label
      gcashLabel.classList.remove('border-brand-600', 'bg-brand-50');
      gcashLabel.classList.add('border-gray-100');
      // Deactivate GCash dot (empty)
      gcashDot.classList.remove('border-4', 'border-brand-600');
      gcashDot.classList.add('border-2', 'border-gray-200');
    } else {
      // Activate GCash label
      gcashLabel.classList.add('border-brand-600', 'bg-brand-50');
      gcashLabel.classList.remove('border-gray-100');
      // Activate GCash dot (filled)
      gcashDot.classList.remove('border-2', 'border-gray-200');
      gcashDot.classList.add('border-4', 'border-brand-600');
      // Deactivate COD label
      codLabel.classList.remove('border-brand-600', 'bg-brand-50');
      codLabel.classList.add('border-gray-100');
      // Deactivate COD dot (empty)
      codDot.classList.remove('border-4', 'border-brand-600');
      codDot.classList.add('border-2', 'border-gray-200');
    }
  }

  document.getElementById('label-cod').addEventListener('click', () => updatePaymentUI('cod'));
  document.getElementById('label-gcash').addEventListener('click', () => updatePaymentUI('gcash'));

  // Parse city and province from address on form submit
  document.getElementById('checkout-form').addEventListener('submit', (e) => {
    const address = e.target.querySelector('[name="shipping_address"]').value;
    const parts = address.split(',').map(s => s.trim());
    document.getElementById('field-province').value = parts[parts.length - 1] || 'Mindanao';
    document.getElementById('field-city').value = parts[parts.length - 2] || 'Mindanao';
  });

  loadCheckoutSummary();
</script>
@endpush
