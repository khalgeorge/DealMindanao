@extends('layouts.app')

@section('meta_title', $hp['home_meta_title'] ?? 'DealMindanao – Verified Mindanao Deals | Hardware, Food & Equipment')
@section('meta_description', $hp['home_meta_description'] ?? 'Shop curated deals from verified Mindanao sellers. Browse hardware, food, and equipment. Order online — pay offline via GCash or Bank Transfer after our team confirms your request.')
@section('meta_keywords', $hp['home_meta_keywords'] ?? 'Mindanao deals, buy online Mindanao, hardware Davao, Bukidnon products, Zamboanga marketplace, GCash payment, Bank Transfer Philippines')
@section('canonical', $hp['home_canonical'] ?: url('/'))

@section('content')

@if(($hp['home_hero_enabled'] ?? '1') !== '0')
<section class="relative overflow-hidden bg-gray-900">
    <div class="relative w-full h-[560px] md:h-[640px] lg:h-[720px]">

        @foreach($hp['home_hero_slides'] as $slideIndex => $slide)
        @php
            $heading   = trim(str_replace(["\r\n", "\r"], ["\n", "\n"], $slide['heading'] ?? ''));
            $highlight = $slide['heading_highlight'] ?? '';
            $hClass    = $slide['highlight_class'] ?? 'text-brand-400';
            $imgUrl    = $slide['image_url'] ?? ($slide['image'] ?? '');
            $pills     = array_filter(array_map('trim', explode(',', $slide['pills'] ?? '')));
            $headingLines = array_filter(array_map(function ($line) use ($highlight, $hClass) {
                $line = trim($line);
                if ($line === '') return null;
                if ($highlight && str_contains($line, $highlight)) {
                    $pos = strpos($line, $highlight);
                    return e(substr($line, 0, $pos))
                         . '<span class="' . e($hClass) . '">' . e($highlight) . '</span>'
                         . e(substr($line, $pos + strlen($highlight)));
                }
                return e($line);
            }, explode("\n", str_replace("\r\n", "\n", $heading))), fn($l) => $l !== null);
            $headingHtml = implode('<br/>', $headingLines);
        @endphp

        <div class="hero-slide absolute inset-0 {{ $slideIndex > 0 ? 'opacity-0 pointer-events-none' : '' }} transition-opacity duration-1000">
            <img src="{{ $imgUrl }}" class="w-full h-full object-cover" alt="{{ $heading }}">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
            <div class="absolute inset-0 flex items-center">
                <div class="container mx-auto px-6 lg:px-16 max-w-7xl">
                    <div class="max-w-2xl">
                        <h1 class="hero-heading font-black text-white leading-[1.1] mb-6" style="font-size: clamp(1.5rem, 4.5vw, 4.5rem);">
                            {!! $headingHtml !!}
                        </h1>
                        @if($slide['subtext'] ?? '')
                        <p class="text-base md:text-lg lg:text-xl text-white/90 font-medium mb-8 max-w-xl leading-relaxed">{{ $slide['subtext'] }}</p>
                        @endif
                        @if($slide['cta_text'] ?? '')
                        <div class="mb-8">
                            <a href="{{ $slide['cta_url'] ?? '#' }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand-600 hover:bg-brand-700 text-white text-base font-bold rounded-lg shadow-xl transition-all transform hover:scale-105">
                                {{ $slide['cta_text'] }}
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                        @endif
                        @if(count($pills))
                        <div class="flex flex-wrap gap-3 mb-6">
                            @foreach($pills as $pill)
                            <span class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white text-sm font-semibold rounded-full">{{ $pill }}</span>
                            @endforeach
                        </div>
                        @endif
                        @if(($slide['badge1'] ?? '') || ($slide['badge2'] ?? ''))
                        <div class="flex flex-wrap items-center gap-6">
                            @if($slide['badge1'] ?? '')
                            <div class="flex items-center gap-2 text-white/90">
                                <svg class="w-5 h-5 {{ $hClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <span class="text-sm font-semibold">{{ $slide['badge1'] }}</span>
                            </div>
                            @endif
                            @if($slide['badge2'] ?? '')
                            <div class="flex items-center gap-2 text-white/90">
                                <svg class="w-5 h-5 {{ $hClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span class="text-sm font-semibold">{{ $slide['badge2'] }}</span>
                            </div>
                            @endif
                        </div>
                        @endif
                        @if($slide['disclaimer'] ?? '')
                        <p class="mt-4 text-xs text-white/70 max-w-lg">{{ $slide['disclaimer'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @if(count($hp['home_hero_slides']) > 1)
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 z-20">
            @foreach($hp['home_hero_slides'] as $si => $__)
            <button onclick="setSlide({{ $si }})" class="slide-indicator w-3 h-3 rounded-full {{ $si === 0 ? 'bg-white' : 'bg-white/50' }} transition-all" aria-label="Slide {{ $si + 1 }}"></button>
            @endforeach
        </div>
        <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center transition-all z-20">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center transition-all z-20">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
        @endif
    </div>
</section>
@endif

{{-- ── Trust Bar ────────────────────────────────────────────────────── --}}
<div class="bg-brand-50 border-y border-gray-100">
    <div class="container mx-auto px-6 lg:px-16 max-w-7xl">
        <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 py-4 text-sm">
            <span class="flex items-center gap-2 font-bold" style="color:#b45309;">
                <svg class="w-4 h-4" style="fill:#f59e0b;" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.366 2.444a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118L10 15.347l-3.954 2.674c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.064 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69L9.049 2.927z"/></svg>
                Trusted by buyers across Mindanao
            </span>
            <span class="hidden sm:block w-px h-4 bg-brand-200"></span>
            <span class="flex items-center gap-2 font-medium text-gray-700">
                <svg class="w-4 h-4 shrink-0" style="color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Verified suppliers only
            </span>
            <span class="hidden sm:block w-px h-4 bg-brand-200"></span>
            <span class="flex items-center gap-2 font-medium text-gray-700">
                <svg class="w-4 h-4 shrink-0" style="color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Managed by DealMindanao team
            </span>
        </div>
    </div>
</div>

@if(($hp['home_highlights_enabled'] ?? '1') !== '0')
<section class="py-20 md:py-15 bg-white">
    <div class="container mx-auto px-6 lg:px-16 max-w-7xl">
        <div class="text-center mb-12 md:mb-16">
            @if($hp['home_highlights_badge'] ?? '')
            <span class="inline-block py-1.5 px-4 bg-accent-100 text-accent-700 text-xs font-bold uppercase tracking-wider rounded-full mb-4">{{ $hp['home_highlights_badge'] }}</span>
            @endif
            <h2 id="highlights-heading" class="font-black text-gray-900 mb-4 whitespace-nowrap overflow-hidden" style="font-size: clamp(1.25rem, 4vw, 3rem);">{{ $hp['home_highlights_heading'] ?? 'Weekly Highlights' }}</h2>
            @if($hp['home_highlights_subtext'] ?? '')
            <p class="text-gray-600 font-medium text-base md:text-lg max-w-2xl mx-auto">{{ $hp['home_highlights_subtext'] }}</p>
            <p class="mt-5 text-center text-sm md:text-base text-gray-500 font-medium italic">🔥 High-demand items — limited availability. Request now to secure your order.</p>
            @endif
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @forelse($featuredProducts as $product)
            @php
                $finalPrice = $product->price - $product->discount;
                $discountPercent = $product->discount > 0 ? round(($product->discount / $product->price) * 100) : 0;
                $imageUrl = product_image_url($product->images ?? []);
            @endphp
            <div class="group bg-white rounded-xl overflow-hidden border border-gray-100 hover:border-brand-200 transition-all duration-300 hover:shadow-xl">
                <div class="h-56 bg-white p-3 overflow-hidden relative">
                    <img src="{{ $imageUrl }}" class="w-full h-full object-contain object-center transition-transform duration-700 group-hover:scale-105" alt="{{ $product->name }}" onerror="this.onerror=null;this.src='/images/unknown-product.svg'">
                    @if($discountPercent > 0)
                    <div class="absolute top-4 right-4"><span class="bg-red-500 text-white px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-wider shadow-lg">-{{ $discountPercent }}%</span></div>
                    @endif
                    @if($product->category?->name)
                    <div class="absolute top-4 left-4"><span class="bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow text-gray-900">{{ $product->category->name }}</span></div>
                    @endif
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <a href="{{ route('product.show', $product->slug) }}" class="px-6 py-3 bg-white text-gray-900 font-black text-sm uppercase tracking-wider rounded-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">Quick View</a>
                    </div>
                </div>
                <div class="border-t border-gray-200"></div>
                <div class="px-6 pt-4 pb-8">
                    @if($product->supplier)
                    <p class="text-brand-600 text-xs font-bold uppercase tracking-wider mb-2">{{ $product->supplier->region ?: 'Mindanao' }}</p>
                    @endif
                    <h3 class="font-black text-gray-900 text-lg mb-3 leading-tight min-h-[56px]">{{ $product->name }}</h3>
                    <div class="flex items-center gap-2 mb-3">
                        <p class="font-black text-2xl text-brand-600">&#8369;{{ number_format($finalPrice, 2) }}</p>
                        @if($discountPercent > 0)<p class="text-sm text-gray-400 line-through">&#8369;{{ number_format($product->price, 2) }}</p>@endif
                    </div>
                    <div class="flex flex-col gap-1 mb-3 text-xs text-gray-500 font-medium">
                        <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-brand-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Verified Supplier</span>
                        <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-brand-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>Mindanao</span>
                        <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-brand-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>Delivery Available</span>
                    </div>
                    <div class="mb-3 px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-lg text-xs font-bold text-amber-700 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                        Limited Stock – Request Now
                    </div>
                    <a href="{{ route('product.show', $product->slug) }}" class="block w-full py-3 bg-gray-900 hover:bg-brand-600 text-white text-center font-bold uppercase tracking-wider rounded-lg transition-all text-sm">Request Order</a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12"><p class="text-gray-500">No featured products available.</p></div>
            @endforelse
        </div>
        @if($hp['home_highlights_cta_text'] ?? '')
        <div class="text-center mt-12 md:mt-16">
            <a href="{{ $hp['home_highlights_cta_url'] ?? route('shop') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gray-900 hover:bg-brand-600 text-white font-bold rounded-lg transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                {{ $hp['home_highlights_cta_text'] }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        @endif
    </div>
</section>
@endif

@if(($hp['home_benefits_enabled'] ?? '1') !== '0' && count($hp['home_benefits_items']))
<section class="py-20 md:py-28 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-16 max-w-7xl">
        <div class="max-w-3xl mx-auto text-center mb-12 md:mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-4">{{ $hp['home_benefits_heading'] ?? 'Why Shop on DealMindanao?' }}</h2>
            @if($hp['home_benefits_subtext'] ?? '')
            <p class="text-base md:text-lg text-gray-600">{{ $hp['home_benefits_subtext'] }}</p>
            @endif
        </div>
        @php
        $iconPaths = [
            'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
        ];
        $bgColors   = ['bg-brand-100','bg-accent-100','bg-brand-100','bg-accent-100'];
        $iconColors = ['text-brand-600','text-accent-600','text-brand-600','text-accent-600'];
        @endphp
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @foreach($hp['home_benefits_items'] as $bi => $benefit)
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-14 h-14 {{ $bgColors[$bi % 4] }} rounded-lg flex items-center justify-center mb-5">
                    <svg class="w-7 h-7 {{ $iconColors[$bi % 4] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$bi % 4] }}"></path>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2">{{ $benefit['title'] ?? '' }}</h3>
                <p class="text-sm md:text-base text-gray-600 leading-relaxed">{{ $benefit['description'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(($hp['home_steps_enabled'] ?? '1') !== '0' && count($hp['home_steps_items']))
<section class="py-20 md:py-28 bg-white">
    <div class="container mx-auto px-6 lg:px-16 max-w-7xl">
        <div class="max-w-3xl mx-auto text-center mb-12 md:mb-16">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-4">{{ $hp['home_steps_heading'] ?? 'What to Expect After You Order' }}</h2>
            @if($hp['home_steps_subtext'] ?? '')
            <p class="text-base md:text-lg text-gray-600">{{ $hp['home_steps_subtext'] }}</p>
            @endif
        </div>
        <div class="max-w-4xl mx-auto">
            <div class="relative">
                <div class="absolute left-7 md:left-8 top-0 bottom-0 w-px bg-brand-200 hidden sm:block"></div>
                @foreach($hp['home_steps_items'] as $si => $step)
                <div class="relative flex gap-6 md:gap-8 {{ !$loop->last ? 'mb-10 md:mb-12' : '' }}">
                    <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 bg-brand-600 rounded-full flex items-center justify-center text-white font-black text-lg md:text-xl shadow-lg relative z-10">{{ $si + 1 }}</div>
                    <div class="flex-1 pt-1 md:pt-2">
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-2 md:mb-3">{{ $step['title'] ?? '' }}</h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed mb-3 md:mb-4">{{ $step['description'] ?? '' }}</p>
                        @if($step['time_label'] ?? '')
                        <div class="inline-flex items-center gap-2 text-xs md:text-sm text-brand-600 font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $step['time_label'] }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-10 flex flex-col items-center gap-3">
                <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-50 border border-brand-200 text-brand-800 text-sm font-semibold rounded-full">
                    <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Every order is manually verified before payment to ensure quality and availability.
                </span>
                <p class="text-center text-sm md:text-base text-gray-500 font-medium italic">DealMindanao ensures every order is verified, secured, and properly handled from start to finish.</p>
            </div>
            @if($hp['home_steps_info_heading'] ?? '')
            <div class="mt-16 p-8 bg-brand-50 rounded-2xl border-2 border-brand-200">
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-brand-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="font-bold text-gray-900 mb-2">{{ $hp['home_steps_info_heading'] }}</h4>
                        @if($hp['home_steps_info_text'] ?? '')
                        <p class="text-gray-600 leading-relaxed mb-3">{{ $hp['home_steps_info_text'] }}</p>
                        @endif
                        @if(($hp['home_steps_info_link1_text'] ?? '') || ($hp['home_steps_info_link2_text'] ?? ''))
                        <div class="flex gap-4">
                            @if($hp['home_steps_info_link1_text'] ?? '')
                            <a href="{{ $hp['home_steps_info_link1_url'] ?? '#' }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">{{ $hp['home_steps_info_link1_text'] }} &rarr;</a>
                            @endif
                            @if($hp['home_steps_info_link2_text'] ?? '')
                            <a href="{{ $hp['home_steps_info_link2_url'] ?? '#' }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">{{ $hp['home_steps_info_link2_text'] }} &rarr;</a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

@if(($hp['home_cta_enabled'] ?? '1') !== '0')
<section class="py-20 md:py-28 bg-gradient-to-br from-brand-600 to-brand-700 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjA1IiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-40"></div>
    <div class="container mx-auto px-6 lg:px-16 max-w-7xl relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-5 md:mb-6 leading-tight">{{ $hp['home_cta_heading'] ?? 'Join the Mindanao Movement' }}</h2>
            @if($hp['home_cta_subtext'] ?? '')
            <p class="text-brand-100 text-base md:text-lg mb-8 md:mb-10 leading-relaxed font-medium">{{ $hp['home_cta_subtext'] }}</p>
            @endif
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if($hp['home_cta_btn1_text'] ?? '')
                <a href="{{ $hp['home_cta_btn1_url'] ?? '#' }}" class="inline-flex items-center justify-center px-8 md:px-10 py-4 md:py-5 bg-white text-brand-700 font-bold text-sm uppercase tracking-wider rounded-lg shadow-xl hover:shadow-2xl hover:bg-gray-50 transition-all">{{ $hp['home_cta_btn1_text'] }}</a>
                @endif
                @if($hp['home_cta_btn2_text'] ?? '')
                <a href="{{ $hp['home_cta_btn2_url'] ?? '#' }}" class="inline-flex items-center justify-center px-8 md:px-10 py-4 md:py-5 border-2 border-white text-white font-bold text-sm uppercase tracking-wider rounded-lg hover:bg-white/10 transition-all">{{ $hp['home_cta_btn2_text'] }}</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

{{-- ── Service Coverage Section ─────────────────────────────────────── --}}
<section class="py-16 md:py-20 bg-gray-50 border-t border-gray-100">
    <div class="container mx-auto px-6 lg:px-16 max-w-7xl">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-gray-900 mb-4">Serving Customers Across Mindanao</h2>
            <p class="text-base md:text-lg text-gray-600 mb-8 leading-relaxed">DealMindanao provides sourcing and delivery services across:</p>
            <div class="flex flex-wrap justify-center gap-3 mb-8">
                @foreach(['Cagayan de Oro', 'Davao City', 'Bukidnon', 'Iligan', 'Northern Mindanao and nearby areas'] as $area)
                <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-brand-200 text-gray-800 text-sm font-semibold rounded-full shadow-sm">
                    <svg class="w-3.5 h-3.5 text-brand-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    {{ $area }}
                </span>
                @endforeach
            </div>
            <p class="text-base text-gray-600 leading-relaxed">We connect customers with reliable suppliers and ensure smooth transactions anywhere in Mindanao.</p>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    let currentSlide = 0;
    const slides     = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.slide-indicator');
    function setSlide(index) {
        currentSlide = index;
        slides.forEach((s, i) => {
            s.style.opacity       = i === index ? '1' : '0';
            s.style.pointerEvents = i === index ? 'auto' : 'none';
        });
        indicators.forEach((d, i) => d.style.backgroundColor = i === index ? 'white' : 'rgba(255,255,255,0.5)');
    }
    function nextSlide() { currentSlide = (currentSlide + 1) % slides.length; setSlide(currentSlide); }
    function prevSlide() { currentSlide = (currentSlide - 1 + slides.length) % slides.length; setSlide(currentSlide); }
    if (slides.length > 1) setInterval(nextSlide, 5000);

    // Auto-fit hero heading font size to prevent text overflow
    function fitHeroHeadings() {
        document.querySelectorAll('.hero-slide').forEach(function(slide) {
            var h1 = slide.querySelector('h1');
            var contentBox = h1 ? h1.closest('.max-w-2xl') : null;
            if (!h1 || !contentBox) return;
            h1.style.fontSize = ''; // reset to CSS default
            var slideH   = slide.offsetHeight;
            var fontSize = parseFloat(window.getComputedStyle(h1).fontSize);
            var minSize  = 16;
            // Shrink heading until all slide content fits within the slide height (with 40px buffer)
            while (contentBox.offsetHeight > slideH - 40 && fontSize > minSize) {
                fontSize -= 1;
                h1.style.fontSize = fontSize + 'px';
            }
        });
    }
    // Auto-fit highlights section heading to one line
    function fitHighlightsHeading() {
        var h2 = document.getElementById('highlights-heading');
        if (!h2) return;
        h2.style.fontSize = '';
        var parent = h2.parentElement;
        var fontSize = parseFloat(window.getComputedStyle(h2).fontSize);
        var minSize = 14;
        while (h2.scrollWidth > parent.clientWidth && fontSize > minSize) {
            fontSize -= 1;
            h2.style.fontSize = fontSize + 'px';
        }
    }
    document.addEventListener('DOMContentLoaded', function() { fitHeroHeadings(); fitHighlightsHeading(); });
    window.addEventListener('resize', function() { fitHeroHeadings(); fitHighlightsHeading(); });
</script>
@endpush
