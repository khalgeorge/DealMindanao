@extends('layouts.app')

@php
    $finalPrice      = $product->displayPrice();
    $discountPercent = $product->discountPercent();
    $isOnPromo       = $product->isOnPromo();
    $imageUrl        = product_image_url($product->images ?? []);

    // SEO Title: custom > auto-generated
    $seoTitle = $product->meta_title
        ?: ($product->name . ' | DealMindanao');

    // SEO Description: custom > short description > fallback
    $seoDescription = $product->meta_description
        ?: \Illuminate\Support\Str::limit(
            $product->description ?? 'Discover this deal on DealMindanao.',
            155
        );

    // SEO Keywords: custom > auto from name + category
    $seoKeywords = $product->meta_keywords
        ?: implode(', ', array_filter([
            $product->name,
            optional($product->category)->name,
            'DealMindanao',
            'Mindanao products',
            'buy online Philippines',
        ]));

    $canonicalUrl = url('/product/' . $product->slug);
@endphp

@section('meta_title',       $seoTitle)
@section('meta_description', 'Buy ' . $product->name . ' from verified Mindanao sellers. ' . \Illuminate\Support\Str::limit($seoDescription, 140) . ' Order online, pay via GCash or Bank Transfer.')
@section('meta_keywords',    $seoKeywords)
@section('canonical',        $canonicalUrl)
@section('og_url',           $canonicalUrl)
@section('meta_robots',      'index,follow')
@if($imageUrl)
    @section('meta_image',   $imageUrl)
@endif

{{-- Structured Data: Product (JSON-LD) --}}
@push('scripts')
@php
    $ldJson = [
        '@context' => 'https://schema.org',
        '@type'    => 'Product',
        'name'        => $product->name,
        'description' => \Illuminate\Support\Str::limit($product->description ?? '', 500),
        'url'         => $canonicalUrl,
        'offers'      => [
            '@type'         => 'Offer',
            'priceCurrency' => 'PHP',
            'price'         => number_format($finalPrice, 2, '.', ''),
            'availability'  => $product->stock_quantity > 0
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock',
            'seller'        => ['@type' => 'Organization', 'name' => 'DealMindanao'],
        ],
    ];
    if ($imageUrl)          { $ldJson['image'] = $imageUrl; }
    if ($product->brand)    { $ldJson['brand'] = ['@type' => 'Brand', 'name' => $product->brand->name]; }
    if ($product->sku)      { $ldJson['sku']   = $product->sku; }
@endphp
<script type="application/ld+json">{{ json_encode($ldJson, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</script>
@endpush

@section('content')
<div class="page-shell py-12">
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
                <img id="main-product-image" src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-contain" onerror="this.onerror=null;this.src='/images/unknown-product.svg'">
            </div>
            
            <!-- Image Gallery -->
            @if(!empty($product->images) && count($product->images) > 1)
            <div class="grid grid-cols-4 gap-4">
                @foreach($product->images as $index => $image)
                    @if($index < 4)
                    <div data-src="{{ product_image_url($product->images ?? [], $index) }}" class="product-thumb aspect-square bg-gray-100 rounded-lg border {{ $index === 0 ? 'border-2 border-brand-500' : 'border border-gray-100' }} overflow-hidden cursor-pointer hover:border-brand-400 transition-all">
                        <img src="{{ product_image_url($product->images ?? [], $index) }}" alt="{{ $product->name }} - Image {{ $index + 1 }}" class="w-full h-full object-contain pointer-events-none" onerror="this.onerror=null;this.src='/images/unknown-product.svg'">
                    </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="grid grid-cols-4 gap-4">
                <div data-src="{{ $imageUrl }}" class="product-thumb aspect-square bg-gray-100 rounded-lg border-2 border-brand-500 overflow-hidden cursor-pointer">
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-contain pointer-events-none" onerror="this.onerror=null;this.src='/images/unknown-product.svg'">
                </div>
            </div>
            @endif
        </div>

        <!-- Info Section -->
        <div class="flex flex-col">
            <div class="mb-8">
                <h1 class="text-4xl font-extrabold capitalize text-gray-900 leading-tight mb-2">{{ $product->name }}</h1>
                @php $hasVariantOptions = !empty($product->variants['options']); @endphp
            </div>

            <!-- Price Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mb-8">
                @if($isOnPromo && $product->promo_label)
                <div class="inline-flex items-center gap-1.5 bg-brand-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg mb-3">
                    {{ $product->promo_label }}
                </div>
                @endif
                <div class="flex items-center gap-4">
                    <span id="display-price" class="text-4xl font-black text-brand-600">₱{{ number_format($finalPrice, 2) }}</span>
                    @if($isOnPromo)
                    <div class="flex flex-col">
                        <span class="text-lg text-gray-400 line-through decoration-brand-200">₱{{ number_format($product->price, 2) }}</span>
                        <span class="text-xs font-bold text-accent-600">Save {{ $discountPercent }}%</span>
                    </div>
                    @endif
                </div>

                @if(!empty($product->variants['attribute']) && !empty($product->variants['options']))
                <!-- Variant Selector -->
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <p class="text-xs font-black uppercase tracking-widest text-gray-500 mb-3">
                        {{ $product->variants['attribute'] }}
                    </p>
                    <div class="flex flex-wrap gap-2" id="variant-options">
                        @foreach($product->variants['options'] as $i => $option)
                        <button type="button"
                                class="variant-btn px-4 py-2 rounded-lg border text-sm font-semibold transition-all
                                       {{ $i === 0 ? 'border-brand-600 bg-brand-50 text-brand-700' : 'border-gray-200 bg-white text-gray-700 hover:border-brand-400' }}"
                                data-price="{{ $option['price'] }}"
                                data-stock="{{ $option['stock'] }}"
                                data-label="{{ $option['label'] }}">
                            {{ $option['label'] }}
                            @if(isset($option['stock']) && $option['stock'] < 1)
                                <span class="ml-1 text-[10px] text-red-400">(Out of stock)</span>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-4 pt-4 border-t border-gray-50 flex items-center gap-2 text-gray-500 text-sm italic">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Offline Payment Only: Pay via GCash or Bank Transfer upon confirmation.
                </div>
            </div>

            <!-- Description -->
            @if(!empty(trim($product->description ?? '')))
            <div class="mb-8">
                <h3 class="font-bold text-gray-900 mb-4 text-lg">About this deal</h3>
                <div class="text-gray-600 leading-relaxed text-lg prose">
                    {{ $product->description }}
                </div>
            </div>
            @endif

            <!-- Product Details -->
            @php
                $specs = array_filter([
                    'Brand'       => $product->brand?->name,
                    'Model Code'  => $product->model_code,
                    'Variant'     => !$hasVariantOptions ? $product->variant : null,
                    'Category'    => $product->category?->name,
                    'Region'      => $product->supplier?->region,
                ]);
            @endphp
            @if(count($specs))
            <div class="mb-10">
                <div class="rounded-lg border border-gray-100 overflow-hidden">
                    <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100">
                        <h4 class="text-xs font-black uppercase tracking-widest text-gray-700">Product Details</h4>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($specs as $label => $value)
                        <div class="flex">
                            <span class="w-44 flex-shrink-0 px-4 py-2.5 text-xs font-semibold text-gray-500 bg-gray-50/50">{{ $label }}</span>
                            <span class="px-4 py-2.5 text-sm text-gray-800">{{ $value }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Technical Specifications -->
            @if(!empty($product->specifications) && count($product->specifications))
            @php $specGroups = array_values(array_filter($product->specifications, fn($g) => !empty($g['group']) && !empty($g['items']))); @endphp
            @if(count($specGroups))
            <div class="mb-10">
                <div class="space-y-4">
                    @foreach($specGroups as $group)
                        <div class="rounded-lg border border-gray-100 overflow-hidden">
                            <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100">
                                <h4 class="text-xs font-black uppercase tracking-widest text-gray-700">{{ $group['group'] }}</h4>
                            </div>
                            <div class="divide-y divide-gray-50">
                                @foreach($group['items'] as $item)
                                    @if(!empty($item['label']))
                                    <div class="flex">
                                        <span class="w-44 flex-shrink-0 px-4 py-2.5 text-xs font-semibold text-gray-500 bg-gray-50/50">{{ $item['label'] }}</span>
                                        <span class="px-4 py-2.5 text-sm text-gray-800">{{ $item['value'] ?? '—' }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif

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
                        <span id="stock-val" class="font-bold text-gray-900">{{ $product->stock_quantity }}</span> units available
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button id="add-to-cart" type="button" class="btn-primary btn-lg flex-1 shadow-brand-200/50 shadow-lg">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Add to Cart
                    </button>
                    <button id="wishlist" type="button" class="btn-secondary btn-lg px-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Disclaimer -->
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm text-amber-900 leading-relaxed mb-2">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        This is an order request only. No online payment is required. Our team will contact you to confirm payment and delivery.
                    </p>
                    <a href="/trust-safety" class="text-xs font-semibold text-amber-800 hover:text-amber-900 underline">Learn about our Trust &amp; Safety practices &rarr;</a>
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
                <div class="flex items-center gap-4 p-4 rounded-lg bg-green-50 text-green-700">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-tight">Manual Order Review</p>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-lg bg-green-50 text-green-700">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-tight">Regional Delivery Support</p>
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

            <div class="mt-6 flex items-center justify-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
                Trustworthy Mindanao Platform &bull; Verified Partners Only
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

    {{-- ======== REVIEWS SECTION ======== --}}
    @php
        $productReviews  = $product->reviews->sortByDesc('created_at');
        $productAvg      = $productReviews->avg('rating') ?? 0;
        $productCount    = $productReviews->count();

        $sellerReviews   = $supplierReviews ?? collect();
        $sellerAvg       = $sellerReviews->avg('rating') ?? 0;
        $sellerCount     = $sellerReviews->count();

        // Check if current user has already reviewed
        $userProductReview  = auth()->check()
            ? $productReviews->firstWhere('user_id', auth()->id())
            : null;
        $userSellerReview   = auth()->check()
            ? $sellerReviews->firstWhere('user_id', auth()->id())
            : null;
    @endphp

    <div class="mt-20 pt-12 border-t border-gray-100">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-8">Reviews</h2>

        {{-- Flash messages --}}
        @if(session('review_success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('review_success') }}
        </div>
        @endif
        @if(session('review_error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('review_error') }}
        </div>
        @endif

        {{-- Tab nav --}}
        <div class="flex gap-1 bg-gray-100 p-1 rounded-xl w-fit mb-8">
            <button id="tab-product-btn" onclick="showTab('product')"
                    class="review-tab-btn px-5 py-2 rounded-lg text-sm font-bold transition-all bg-white text-gray-900 shadow-sm">
                Product Reviews
                @if($productCount)
                <span class="ml-1.5 px-1.5 py-0.5 bg-brand-600 text-white rounded-full text-[10px] font-black">{{ $productCount }}</span>
                @endif
            </button>
            <button id="tab-seller-btn" onclick="showTab('seller')"
                    class="review-tab-btn px-5 py-2 rounded-lg text-sm font-bold transition-all text-gray-500">
                Seller Reviews
                @if($sellerCount)
                <span class="ml-1.5 px-1.5 py-0.5 bg-gray-400 text-white rounded-full text-[10px] font-black">{{ $sellerCount }}</span>
                @endif
            </button>
        </div>

        {{-- ── PRODUCT REVIEWS TAB ── --}}
        <div id="tab-product" class="review-tab">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Left: Summary + Form --}}
                <div>
                    {{-- Overall rating --}}
                    @if($productCount)
                    <div class="mb-6 p-5 bg-gray-50 rounded-xl text-center">
                        <p class="text-5xl font-black text-gray-900 mb-1">{{ number_format($productAvg, 1) }}</p>
                        <div class="flex justify-center gap-0.5 mb-1">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($productAvg) ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 font-semibold">{{ $productCount }} review{{ $productCount !== 1 ? 's' : '' }}</p>
                    </div>
                    @endif

                    {{-- Review form --}}
                    @auth
                        @if($userProductReview)
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800 font-semibold">
                            ✓ You've already submitted a review for this product.
                        </div>
                        @else
                        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <h3 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wide">Write a Review</h3>
                            <form action="{{ route('reviews.product.store', $product->slug) }}" method="POST">
                                @csrf
                                {{-- Star picker --}}
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Your Rating</label>
                                    <div class="flex gap-1 star-picker" data-for="product_rating">
                                        @for($i = 1; $i <= 5; $i++)
                                        <button type="button" data-value="{{ $i }}"
                                                class="star-btn text-gray-300 hover:text-amber-400 transition-colors">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="product_rating" value="">
                                    @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">Title <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                                    <input type="text" name="title" maxlength="150" value="{{ old('title') }}"
                                           class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
                                           placeholder="Summarise your experience">
                                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">Review <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                                    <textarea name="body" rows="4" maxlength="2000"
                                              class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"
                                              placeholder="Tell others what you think...">{{ old('body') }}</textarea>
                                    @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <button type="submit" class="btn-primary w-full">Submit Review</button>
                                <p class="text-[11px] text-gray-400 text-center mt-2">Reviews are approved before appearing publicly.</p>
                            </form>
                        </div>
                        @endif
                    @else
                    <div class="p-5 bg-gray-50 border border-gray-200 rounded-xl text-center">
                        <p class="text-sm text-gray-600 mb-3">Sign in to leave a review.</p>
                        <a href="{{ route('login') }}" class="btn-primary text-sm">Log In</a>
                    </div>
                    @endauth
                </div>

                {{-- Right: Approved reviews list --}}
                <div class="lg:col-span-2 space-y-5">
                    @forelse($productReviews as $review)
                    <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div>
                                <div class="flex gap-0.5 mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>
                                @if($review->title)
                                <h4 class="font-bold text-gray-900 text-sm">{{ $review->title }}</h4>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-400 font-semibold whitespace-nowrap">{{ $review->created_at->format('M j, Y') }}</p>
                        </div>
                        @if($review->body)
                        <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ $review->body }}</p>
                        @endif
                        <p class="text-xs text-gray-500 font-bold">— {{ $review->user->name ?? 'Anonymous' }}</p>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                        <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <p class="font-semibold text-sm">No reviews yet — be the first!</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </div>{{-- end tab-product --}}

        {{-- ── SELLER REVIEWS TAB ── --}}
        <div id="tab-seller" class="review-tab hidden">
            @if($product->supplier)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Left: Summary + Form --}}
                <div>
                    @if($sellerCount)
                    <div class="mb-6 p-5 bg-gray-50 rounded-xl text-center">
                        <p class="text-5xl font-black text-gray-900 mb-1">{{ number_format($sellerAvg, 1) }}</p>
                        <div class="flex justify-center gap-0.5 mb-1">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($sellerAvg) ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-500 font-semibold">{{ $sellerCount }} review{{ $sellerCount !== 1 ? 's' : '' }} for <span class="text-gray-700">{{ $product->supplier->name }}</span></p>
                    </div>
                    @endif

                    @auth
                        @if($userSellerReview)
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800 font-semibold">
                            ✓ You've already reviewed this seller.
                        </div>
                        @else
                        <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <h3 class="font-bold text-gray-900 mb-1 text-sm uppercase tracking-wide">Review the Seller</h3>
                            <p class="text-xs text-gray-500 mb-4">{{ $product->supplier->name }}</p>
                            <form action="{{ route('reviews.supplier.store', $product->supplier) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Your Rating</label>
                                    <div class="flex gap-1 star-picker" data-for="seller_rating">
                                        @for($i = 1; $i <= 5; $i++)
                                        <button type="button" data-value="{{ $i }}"
                                                class="star-btn text-gray-300 hover:text-amber-400 transition-colors">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="seller_rating" value="">
                                </div>
                                <div class="mb-3">
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">Title <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                                    <input type="text" name="title" maxlength="150"
                                           class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
                                           placeholder="Summarise your experience">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">Review <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                                    <textarea name="body" rows="4" maxlength="2000"
                                              class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"
                                              placeholder="How was the seller?"></textarea>
                                </div>
                                <button type="submit" class="btn-primary w-full">Submit Review</button>
                                <p class="text-[11px] text-gray-400 text-center mt-2">Reviews are approved before appearing publicly.</p>
                            </form>
                        </div>
                        @endif
                    @else
                    <div class="p-5 bg-gray-50 border border-gray-200 rounded-xl text-center">
                        <p class="text-sm text-gray-600 mb-3">Sign in to review this seller.</p>
                        <a href="{{ route('login') }}" class="btn-primary text-sm">Log In</a>
                    </div>
                    @endauth
                </div>

                {{-- Right: Approved seller reviews --}}
                <div class="lg:col-span-2 space-y-5">
                    @forelse($sellerReviews as $review)
                    <div class="p-5 bg-white border border-gray-100 rounded-xl shadow-sm">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <div>
                                <div class="flex gap-0.5 mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>
                                @if($review->title)
                                <h4 class="font-bold text-gray-900 text-sm">{{ $review->title }}</h4>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-400 font-semibold whitespace-nowrap">{{ $review->created_at->format('M j, Y') }}</p>
                        </div>
                        @if($review->body)
                        <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ $review->body }}</p>
                        @endif
                        <p class="text-xs text-gray-500 font-bold">— {{ $review->user->name ?? 'Anonymous' }}</p>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                        <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <p class="font-semibold text-sm">No seller reviews yet — be the first!</p>
                    </div>
                    @endforelse
                </div>

            </div>
            @else
            <p class="text-gray-500 text-sm">No seller information available for this product.</p>
            @endif
        </div>{{-- end tab-seller --}}

    </div>{{-- end reviews section --}}

</div>
@endsection

@push('scripts')
<script>
    // ── Tab switcher ──────────────────────────────────────────────────────────
    function showTab(tab) {
        document.querySelectorAll('.review-tab').forEach(el => el.classList.add('hidden'));
        document.getElementById('tab-' + tab).classList.remove('hidden');

        document.querySelectorAll('.review-tab-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
            btn.classList.add('text-gray-500');
        });
        const active = document.getElementById('tab-' + tab + '-btn');
        if (active) {
            active.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
            active.classList.remove('text-gray-500');
        }
    }

    // ── Interactive star pickers ──────────────────────────────────────────────
    document.querySelectorAll('.star-picker').forEach(picker => {
        const targetId = picker.dataset.for;
        const input    = document.getElementById(targetId);
        const btns     = picker.querySelectorAll('.star-btn');

        function highlight(value) {
            btns.forEach(btn => {
                const v = parseInt(btn.dataset.value, 10);
                btn.classList.toggle('text-amber-400', v <= value);
                btn.classList.toggle('text-gray-300',  v >  value);
            });
        }

        btns.forEach(btn => {
            btn.addEventListener('mouseenter', () => highlight(parseInt(btn.dataset.value)));
            btn.addEventListener('mouseleave', () => highlight(parseInt(input.value || 0)));
            btn.addEventListener('click', () => {
                input.value = btn.dataset.value;
                highlight(parseInt(btn.dataset.value));
            });
        });
    });
    // --- Constants (server-rendered) ---
    const productId      = {{ $product->id }};
    const basePrice      = {{ (float) $finalPrice }};
    const baseStock      = {{ (int) $product->stock_quantity }};
    const discountPct    = {{ $discountPercent }};

    // --- Mutable state ---
    let maxStock        = baseStock;
    let currentPrice    = basePrice;
    let selectedVariant = @json(!$hasVariantOptions ? ($product->variant ?? null) : null);
    let quantity        = 1;

    // --- DOM refs (declared first so helpers can use them) ---
    const minusBtn  = document.getElementById('minus-qty');
    const plusBtn   = document.getElementById('plus-qty');
    const qtyVal    = document.getElementById('qty-val');
    const priceEl   = document.getElementById('display-price');
    const stockEl   = document.getElementById('stock-val');

    // --- Helpers ---
    function updateQuantity(newQty) {
        quantity = Math.max(1, Math.min(newQty, maxStock));
        if (qtyVal)  qtyVal.textContent  = quantity;
        if (stockEl) stockEl.textContent = maxStock - quantity;
    }

    function formatPrice(amount) {
        return '₱' + amount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function applyVariant(btn) {
        document.querySelectorAll('.variant-btn').forEach(b => {
            b.classList.remove('border-brand-600', 'bg-brand-50', 'text-brand-700');
            b.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
        });
        btn.classList.add('border-brand-600', 'bg-brand-50', 'text-brand-700');
        btn.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');

        selectedVariant = btn.dataset.label;
        currentPrice    = parseFloat(btn.dataset.price);
        maxStock        = parseInt(btn.dataset.stock, 10);

        if (priceEl) priceEl.textContent = formatPrice(currentPrice);
        updateQuantity(1);
    }

    // --- Quantity controls ---
    if (minusBtn) minusBtn.addEventListener('click', () => updateQuantity(quantity - 1));
    if (plusBtn)  plusBtn.addEventListener('click',  () => updateQuantity(quantity + 1));

    // --- Variant selector ---
    const variantBtns = document.querySelectorAll('.variant-btn');
    if (variantBtns.length) {
        // Auto-select first option on page load
        applyVariant(variantBtns[0]);
        variantBtns.forEach(btn => btn.addEventListener('click', function () { applyVariant(this); }));
    }

    // --- Thumbnail gallery ---
    const mainImage = document.getElementById('main-product-image');
    document.querySelectorAll('.product-thumb').forEach(thumb => {
        thumb.addEventListener('click', function () {
            if (mainImage) mainImage.src = this.dataset.src;
            document.querySelectorAll('.product-thumb').forEach(t => {
                t.classList.remove('border-2', 'border-brand-500');
                t.classList.add('border', 'border-gray-100');
            });
            this.classList.remove('border', 'border-gray-100');
            this.classList.add('border-2', 'border-brand-500');
        });
    });

    // --- Wishlist toggle ---
    (function () {
        const btn      = document.getElementById('wishlist');
        const svg      = btn ? btn.querySelector('svg') : null;
        const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');

        function setWishlistState(active) {
            if (!svg) return;
            if (active) {
                svg.setAttribute('fill', 'currentColor');
                btn.classList.add('text-red-500');
                btn.classList.remove('text-gray-600');
            } else {
                svg.setAttribute('fill', 'none');
                btn.classList.remove('text-red-500');
                btn.classList.add('text-gray-600');
            }
        }

        setWishlistState(wishlist.includes(productId));

        if (btn) {
            btn.addEventListener('click', function () {
                const list = JSON.parse(localStorage.getItem('wishlist') || '[]');
                const idx  = list.indexOf(productId);
                if (idx === -1) {
                    list.push(productId);
                    setWishlistState(true);
                } else {
                    list.splice(idx, 1);
                    setWishlistState(false);
                }
                localStorage.setItem('wishlist', JSON.stringify(list));
            });
        }
    })();

    // --- Add to cart ---
    document.getElementById('add-to-cart').addEventListener('click', () => {
        if (maxStock < 1) {
            alert('This product is out of stock.');
            return;
        }
        if (quantity > maxStock) {
            alert('Not enough stock available.');
            return;
        }
        // Require a variant selection if variants exist
        if (variantBtns.length && !selectedVariant) {
            alert('Please select an option first.');
            return;
        }

        const productName = @json($product->name);
        const images      = @json($product->images ?? []);
        const companyName = @json($product->supplier?->name ?? '');
        // Cart key includes variant so different options are separate line items
        const cartKey     = selectedVariant ? `${productId}__${selectedVariant}` : String(productId);
        const displayName = selectedVariant ? `${productName} (${selectedVariant})` : productName;

        const cart     = JSON.parse(localStorage.getItem('cart') || '[]');
        const existing = cart.find(item => item.cart_key === cartKey);

        if (existing) {
            const newQty = existing.quantity + quantity;
            if (newQty > maxStock) {
                alert('Cannot add more than available stock.');
                return;
            }
            existing.quantity = newQty;
        } else {
            cart.push({
                id:                  productId,
                cart_key:            cartKey,
                name:                displayName,
                variant:             selectedVariant,
                price:               currentPrice,
                discount_percentage: discountPct,
                quantity:            quantity,
                stock_quantity:      maxStock,
                supplier:            companyName,
                images:              images
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        window.dispatchEvent(new Event('cart-updated'));

        // Reduce available stock by the purchased quantity
        maxStock = Math.max(0, maxStock - quantity);
        updateQuantity(1);

        const addBtn  = document.getElementById('add-to-cart');
        const orig    = addBtn.innerHTML;
        addBtn.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Added!';
        addBtn.disabled = true;
        setTimeout(() => { addBtn.innerHTML = orig; addBtn.disabled = false; }, 1500);

        quantity = 1;
        updateQuantity(1);
    });
</script>
@endpush
