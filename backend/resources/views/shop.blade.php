@extends('layouts.app')

@section('meta_title', 'Shop Deals | DealMindanao')
@section('meta_description', 'Browse curated local deals from trusted partners across Mindanao.')

@section('content')
<!-- Page Header -->
<div class="bg-white border-b border-gray-100">
    <div class="container mx-auto px-6 lg:px-16 max-w-7xl py-8">
        <h1 class="text-3xl font-bold text-gray-900">Shop Deals</h1>
        <p class="text-gray-600 mt-2">Browse curated hardware and equipment deals from trusted local sellers.</p>
    </div>
</div>

<!-- Shop Grid -->
<div class="container mx-auto px-6 lg:px-16 max-w-7xl py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-64 space-y-6 flex-shrink-0">
            <form method="GET" action="{{ route('shop') }}" id="filter-form" class="space-y-6">
                <!-- Search -->
                <div>
                    <label class="block mb-2 font-bold uppercase tracking-tight text-xs text-gray-500">Search</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input type="text" name="q" value="{{ $search }}" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent" placeholder="Search keywords...">
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block mb-3 font-bold uppercase tracking-tight text-xs text-gray-500">Category</label>
                    <select name="category" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Company -->
                <div>
                    <label class="block mb-3 font-bold uppercase tracking-tight text-xs text-gray-500">Company</label>
                    <select name="company" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        <option value="">All Companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected((string) $companyId === (string) $company->id)>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label class="block mb-3 font-bold uppercase tracking-tight text-xs text-gray-500">Price Range</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent" placeholder="Min">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent" placeholder="Max">
                    </div>
                </div>

                <!-- Sorting -->
                <div>
                    <label class="block mb-3 font-bold uppercase tracking-tight text-xs text-gray-500">Sort By</label>
                    <select name="sort" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                        <option value="latest" @selected($sort === 'latest')>Newest First</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Price: High to Low</option>
                        <option value="name" @selected($sort === 'name')>Name (A-Z)</option>
                    </select>
                </div>

                <!-- Discount Only -->
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="discount_only" value="1" class="w-4 h-4 text-brand-600 rounded border-gray-300 focus:ring-brand-500" @checked(request('discount_only'))>
                        <span class="text-sm text-gray-700">Discount only</span>
                    </label>
                </div>

                <!-- Filter Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg transition-all">
                        Apply
                    </button>
                    <a href="{{ route('shop') }}" class="px-4 py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-lg transition-all flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </a>
                </div>
            </form>
        </aside>

        <!-- Results Grid -->
        <div class="flex-1">
            <!-- Grid Header -->
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">
                    <span class="font-bold text-gray-900">{{ $products->total() }}</span> products found
                </p>
            </div>

            @if($products->count())
                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        @php
                            $finalPrice = $product->price - $product->discount;
                            $discountPercent = $product->discount > 0 ? round(($product->discount / $product->price) * 100) : 0;
                            $imageUrl = !empty($product->images) ? \Illuminate\Support\Facades\Storage::url($product->images[0]) : 'https://via.placeholder.com/400';
                        @endphp
                        <div class="group bg-white rounded-xl overflow-hidden border border-gray-100 hover:border-brand-200 transition-all duration-300 hover:shadow-xl">
                            <div class="aspect-[4/5] bg-gray-100 overflow-hidden relative">
                                <img src="{{ $imageUrl }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $product->name }}">
                                
                                @if($discountPercent > 0)
                                <div class="absolute top-4 right-4">
                                    <span class="bg-red-500 text-white px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-wider shadow-lg">
                                        -{{ $discountPercent }}%
                                    </span>
                                </div>
                                @endif
                                
                                <div class="absolute top-4 left-4">
                                    <span class="bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow text-gray-900">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                                
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <a href="{{ route('product.show', $product->slug) }}" class="px-6 py-3 bg-white text-gray-900 font-black text-sm uppercase tracking-wider rounded-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                        Quick View
                                    </a>
                                </div>
                            </div>
                            
                            <div class="p-5">
                                <p class="text-brand-600 text-xs font-bold uppercase tracking-wider mb-2">
                                    {{ $product->company->name }}
                                </p>
                                
                                <h3 class="font-black text-gray-900 text-lg mb-3 leading-tight min-h-[56px]">
                                    {{ $product->name }}
                                </h3>
                                
                                <div class="flex items-center gap-2 mb-4">
                                    <p class="font-black text-2xl text-brand-600">₱{{ number_format($finalPrice, 2) }}</p>
                                    @if($discountPercent > 0)
                                    <p class="text-sm text-gray-400 line-through">₱{{ number_format($product->price, 2) }}</p>
                                    @endif
                                </div>
                                
                                <a href="{{ route('product.show', $product->slug) }}" class="block w-full py-3 bg-gray-900 hover:bg-brand-600 text-white text-center font-bold uppercase tracking-wider rounded-lg transition-all text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">No products found</h2>
                    <p class="text-gray-500 mb-8">Try adjusting your search or filters.</p>
                    <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-lg transition-all">
                        Clear All Filters
                    </a>
                </div>
            @endif

            <!-- Footer Note -->
            <div class="mt-12 text-center">
                <p class="text-sm text-gray-500">Prices and availability are subject to confirmation after order review.</p>
            </div>
        </div>
    </div>
</div>
@endsection
