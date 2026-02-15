@extends('layouts.app')

@php
    $finalPrice = $product->price - $product->discount;
    $discountPercent = $product->discount > 0 ? round(($product->discount / $product->price) * 100) : 0;
    $imageUrl = !empty($product->images) ? \Illuminate\Support\Facades\Storage::url($product->images[0]) : 'https://via.placeholder.com/800';
    $metaTitle = $product->meta_title ?: ($product->name . ' | DealMindanao');
    $metaDescription = $product->meta_description
        ?: \Illuminate\Support\Str::limit($product->description ?? 'Discover this deal from DealMindanao.', 160);
@endphp

@section('meta_title', $metaTitle)
@section('meta_description', $metaDescription)
@if($imageUrl)
    @section('meta_image', $imageUrl)
@endif

@section('content')
<div class="container mx-auto px-6 lg:px-16 max-w-7xl py-12">
    <!-- Breadcrumb -->
    <nav class="mb-8 hidden md:block">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">Home</a></li>
            <li class="flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('shop') }}" class="hover:text-brand-600 transition-colors">Shop</a>
            </li>
            <li class="flex items-center space-x-2 text-gray-900 font-medium">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span>{{ $product->name }}</span>
            </li>
        </ol>
    </nav>

    <!-- Product Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        
        <!-- Image Section -->
        <div class="sticky top-24">
            <div class="aspect-square bg-white rounded-lg overflow-hidden shadow-lg border border-gray-100 mb-6">
                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
            
            <!-- Image Gallery -->
            @if(!empty($product->images) && count($product->images) > 1)
            <div class="grid grid-cols-4 gap-4">
                @foreach($product->images as $index => $image)
                    @if($index < 4)
                    <div class="aspect-square bg-gray-100 rounded-lg border {{ $index === 0 ? 'border-brand-500' : 'border-gray-100' }} overflow-hidden cursor-pointer hover:border-brand-200 transition-all">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($image) }}" alt="{{ $product->name }} - Image {{ $index + 1 }}" class="w-full h-full object-cover">
                    </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="grid grid-cols-4 gap-4">
                <div class="aspect-square bg-gray-100 rounded-lg border-2 border-brand-500 overflow-hidden">
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                @for($i = 1; $i < 4; $i++)
                <div class="aspect-square bg-gray-200 rounded-lg border border-gray-100 overflow-hidden flex items-center justify-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                @endfor
            </div>
            @endif
        </div>

        <!-- Info Section -->
        <div class="flex flex-col">
            <div class="mb-8">
                <span class="inline-block px-3 py-1 bg-brand-50 text-brand-700 text-xs font-bold uppercase tracking-widest rounded-full mb-4">
                    {{ $product->category->name }}
                </span>
                <h1 class="text-4xl font-extrabold text-gray-900 leading-tight mb-2">{{ $product->name }}</h1>
                <p class="text-gray-500 text-sm">by <span class="font-semibold">{{ $product->company->name }}</span></p>
            </div>

            <!-- Price Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mb-8">
                <div class="flex items-center gap-4">
                    <span class="text-4xl font-black text-brand-600">₱{{ number_format($finalPrice, 2) }}</span>
                    @if($discountPercent > 0)
                    <div class="flex flex-col">
                        <span class="text-lg text-gray-400 line-through decoration-brand-200">₱{{ number_format($product->price, 2) }}</span>
                        <span class="text-xs font-bold text-accent-600">Save {{ $discountPercent }}%</span>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-50 flex items-center gap-2 text-gray-500 text-sm italic">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Offline Payment Only: Pay via GCash or COD upon confirmation.
                </div>
            </div>

            <!-- Description -->
            <div class="mb-10">
                <h3 class="font-bold text-gray-900 mb-4 text-lg">About this deal</h3>
                <div class="text-gray-600 leading-relaxed text-base prose max-w-none">
                    {{ $product->description ?? 'No description available.' }}
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-6">
                <div class="flex items-center gap-6">
                    <div class="flex items-center bg-gray-100 rounded-lg p-1">
                        <button type="button" id="minus-qty" class="w-12 h-12 flex items-center justify-center text-gray-600 hover:text-brand-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <span id="qty-val" class="w-12 text-center font-bold text-lg">1</span>
                        <button type="button" id="plus-qty" class="w-12 h-12 flex items-center justify-center text-gray-600 hover:text-brand-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500">
                        <span class="font-bold text-gray-900">{{ $product->stock }}</span> units available
                    </p>
                </div>

                <form action="{{ route('cart.add', $product->id) }}" method="POST" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="quantity" id="quantity-input" value="1">
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Add to Cart
                        </button>
                        <button type="button" class="px-6 py-4 border-2 border-gray-300 hover:border-brand-500 rounded-lg transition-all flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
                
                <!-- Disclaimer -->
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm text-amber-900 leading-relaxed mb-2">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        This is an order request only. No online payment is required. Our team will contact you to confirm payment and delivery.
                    </p>
                </div>
            </div>

            <!-- Guarantee Signals -->
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center gap-4 p-4 rounded-lg bg-green-50 text-green-700">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-tight">Verified Mindanao Partner</p>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-lg bg-brand-50 text-brand-700">
                    <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-tight">Fast Regional Shipping</p>
                </div>
            </div>

            <!-- Back to Shop -->
            <div class="mt-8">
                <a href="{{ route('shop') }}" class="inline-flex items-center text-brand-600 hover:text-brand-700 font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Shop
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Quantity controls
    const minusBtn = document.getElementById('minus-qty');
    const plusBtn = document.getElementById('plus-qty');
    const qtyVal = document.getElementById('qty-val');
    const quantityInput = document.getElementById('quantity-input');
    const maxStock = {{ $product->stock }};
    
    let quantity = 1;
    
    function updateQuantity(newQty) {
        quantity = Math.max(1, Math.min(newQty, maxStock));
        qtyVal.textContent = quantity;
        quantityInput.value = quantity;
    }
    
    minusBtn.addEventListener('click', () => updateQuantity(quantity - 1));
    plusBtn.addEventListener('click', () => updateQuantity(quantity + 1));
</script>
@endpush
