@extends('layouts.app')

@section('meta_title', 'Checkout | DealMindanao')
@section('meta_description', 'Confirm your details to place an offline payment request.')

@section('content')
<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h1>Checkout</h1>
        <p>Confirm your details so we can coordinate offline payment.</p>
    </div>
    <a href="/cart" class="btn-secondary">Back to Cart</a>
</div>

<div class="mt-6 space-y-6">
    <div class="alert-warning">
        ⚠️ This is an order request only. No online payment is required.
        <p class="mt-1 text-sm text-amber-700">We will reach out to confirm availability and payment instructions.</p>
    </div>

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
        <div class="card">
            <form id="checkoutForm" class="grid gap-4" method="POST" action="/checkout">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label>Full Name</label>
                        <input name="customer_name" class="input" placeholder="Your full name" value="{{ old('customer_name') }}" required>
                    </div>
                    <div>
                        <label>Email</label>
                        <input name="email" class="input" type="email" placeholder="you@email.com" value="{{ old('email') }}" required>
                    </div>
                    <div>
                        <label>Phone</label>
                        <input name="phone" class="input" type="tel" placeholder="09xx xxx xxxx" value="{{ old('phone') }}" required>
                    </div>
                    <div>
                        <label>City / Address</label>
                        <input name="address" class="input" placeholder="City and barangay" value="{{ old('address') }}" required>
                    </div>
                </div>

                <div>
                    <label>Notes</label>
                    <textarea name="notes" class="textarea" rows="4" placeholder="Preferred pickup time, notes, or questions">{{ old('notes') }}</textarea>
                </div>

                <div class="hidden sm:flex items-center justify-between gap-4">
                    <p class="text-sm text-gray-500">We will confirm your request within 24 hours.</p>
                    <button class="btn-primary" type="submit">
                        Submit Order Request
                    </button>
                </div>
            </form>
        </div>

        @isset($orderSummary)
            <div class="card h-fit">
                <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                <div class="mt-4 space-y-3">
                    @foreach($orderSummary['items'] ?? [] as $item)
                        <div class="flex items-center justify-between text-sm">
                            <div>
                                <p class="font-medium text-gray-900">{{ $item['name'] ?? 'Item' }}</p>
                                <p class="text-xs text-gray-500">Qty: {{ $item['qty'] ?? 1 }}</p>
                            </div>
                            <p class="font-semibold text-gray-900">₱{{ number_format($item['subtotal'] ?? 0, 2) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 border-t border-gray-100 pt-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Total</span>
                        <span class="text-base font-semibold text-brand">₱{{ number_format($orderSummary['total'] ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        @endisset
    </div>
</div>

<div class="fixed inset-x-0 bottom-0 z-30 border-t border-gray-200 bg-white/95 p-4 shadow-lg sm:hidden">
    <button class="btn-primary w-full" type="submit" form="checkoutForm">
        Submit Order Request
    </button>
    <p class="mt-2 text-center text-xs text-gray-500">Offline payment. We will confirm before you pay.</p>
</div>
@endsection
