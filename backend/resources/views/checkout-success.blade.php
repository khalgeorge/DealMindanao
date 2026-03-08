@extends('layouts.app')

@section('meta_title', 'Order Request Received | DealMindanao')
@section('meta_description', 'Your order request is received. We will contact you to arrange offline payment.')

@section('content')
<div class="py-24">
<div class="card max-w-xl mx-auto text-center">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-50 text-3xl">
        ✅
    </div>

    <h1 class="mt-5 text-2xl font-bold">
        Order Request Received!
    </h1>

    <p class="text-gray-600 mt-2">
        Thank you for your order. Our team will review your request and contact you shortly to arrange offline payment.
    </p>

    @if(session('order_number'))
        <div class="mt-6 rounded-xl border border-gray-100 bg-appbg px-4 py-3 text-left">
            <p class="text-xs text-gray-500">Order Number</p>
            <p class="text-lg font-semibold text-apptext">{{ session('order_number') }}</p>
        </div>
    @endif

    <div class="alert-warning mt-6">
        ⚠️ Please do not send any payment yet. Wait for our confirmation message.
    </div>

    <div class="mt-6 flex flex-wrap justify-center gap-3">
        <a href="/shop" class="btn-primary">
            Continue Browsing Deals
        </a>
        <a href="/" class="btn-secondary">
            Back to Home
        </a>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
  // Clear the cart from localStorage now that the order is placed
  localStorage.removeItem('cart');
  window.dispatchEvent(new Event('cart-updated'));
</script>
@endpush
