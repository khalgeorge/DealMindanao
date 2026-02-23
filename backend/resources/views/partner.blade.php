@extends('layouts.app')

@section('meta_title', $s['partner_meta_title'] ?? 'Become a DealMindanao Partner – Sell Your Mindanao Products Online')
@section('meta_description', $s['partner_meta_description'] ?? 'Join DealMindanao as a verified seller. Showcase your Mindanao products to buyers across Davao, Bukidnon, and Zamboanga. Orders are confirmed offline for secure, region-first commerce.')
@section('meta_keywords', $s['partner_meta_keywords'] ?? 'sell online Mindanao, DealMindanao partner, local Mindanao seller, Davao online market')
@section('canonical', $s['partner_canonical'] ?: url('/partner'))

@section('content')

{{-- ══════════════════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════════════════ --}}
@if(($s['partner_hero_enabled'] ?? '1') == '1')
<section class="relative bg-gray-900 text-white overflow-hidden md:[max-height:68vh]">
    <div class="absolute inset-0 bg-gradient-to-br from-gray-800 via-gray-900 to-gray-950 pointer-events-none"></div>

    <div class="container mx-auto px-6 py-20 md:py-24 relative z-10 text-center">

        @if(!empty($s['partner_hero_badge']))
        <div class="mb-6">
            <span class="inline-flex items-center gap-2 py-1.5 px-4 bg-brand-600/20 border border-brand-500/40 text-brand-300 text-xs font-bold uppercase tracking-widest rounded-full">
                <span class="w-1.5 h-1.5 bg-brand-400 rounded-full animate-pulse"></span>
                {{ $s['partner_hero_badge'] }}
            </span>
        </div>
        @endif

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-[1.08] tracking-tight mb-6 max-w-4xl mx-auto">
            <span class="text-white">{{ $s['partner_hero_title'] }}</span><br class="hidden sm:block">
            <span class="text-brand-400">{{ $s['partner_hero_title_highlight'] }}</span>
        </h1>

        <p class="text-lg text-gray-300 max-w-2xl mx-auto leading-relaxed mb-10">{{ $s['partner_hero_description'] }}</p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8">
            <a href="{{ $s['partner_hero_cta_link'] }}"
               class="sm:w-auto sm:max-w-[300px] inline-flex items-center justify-center btn-primary px-10 py-4 rounded-xl font-bold text-base shadow-lg shadow-brand-900/50 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-900/60 transition-all duration-200 focus-visible:ring-offset-gray-900">
                {{ $s['partner_hero_cta_label'] }}
            </a>
        </div>

        <p class="text-sm text-gray-500">
            <span class="text-brand-400">✔</span> Free onboarding
            &nbsp;·&nbsp;
            <span class="text-brand-400">✔</span> No upfront fees
            &nbsp;·&nbsp;
            <span class="text-brand-400">✔</span> Local Mindanao support
        </p>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════
     BENEFITS
══════════════════════════════════════════════════════════════════ --}}
@if(($s['partner_features_enabled'] ?? '1') == '1')
<section class="py-24 pb-20 md:pb-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-3">Why Partner With Us?</h2>
            <p class="text-gray-500 max-w-xl mx-auto">Everything you need to sell online — without the complexity.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach([1,2,3] as $n)
            @if(($s['partner_card'.$n.'_enabled'] ?? '1') == '1')
            <div class="group flex flex-col items-center text-center p-8 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1.5 transition-all duration-200">
                <div class="w-16 h-16 bg-brand-50 text-brand-600 rounded-2xl flex items-center justify-center mb-6 text-2xl font-black italic shadow-sm group-hover:bg-brand-600 group-hover:text-white group-hover:shadow-md transition-all duration-200">
                    {{ $s['partner_card'.$n.'_number'] }}
                </div>
                <h3 class="font-black text-lg mb-3 text-gray-900 tracking-tight">{{ $s['partner_card'.$n.'_title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $s['partner_card'.$n.'_description'] }}</p>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════
     HOW PARTNERING WORKS
══════════════════════════════════════════════════════════════════ --}}
@if(($s['partner_hiw_enabled'] ?? '1') == '1' && isset($hiwSteps) && $hiwSteps->isNotEmpty())
<section class="py-20 md:py-24 bg-gray-50 border-y border-gray-200">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-black text-center text-gray-900 tracking-tight mb-3">{{ $s['partner_hiw_title'] }}</h2>

        <div class="max-w-4xl mx-auto">

            {{-- DESKTOP: grid with a single shared connector line behind all bubbles --}}
            <div class="hidden md:block relative">
                <div class="absolute top-5 left-[calc(100%/6)] right-[calc(100%/6)] h-px bg-brand-200" aria-hidden="true"></div>
                <ol class="grid grid-cols-3 gap-6 list-none p-0 m-0">
                    @foreach($hiwSteps as $step)
                    <li class="flex flex-col items-center text-center">
                        <div class="relative z-10 w-10 h-10 mb-5 bg-brand-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-gray-50">
                            {{ $loop->iteration }}
                        </div>
                        <div class="w-full px-5 py-5 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $step->step_text }}</p>
                        </div>
                    </li>
                    @endforeach
                </ol>
            </div>

            {{-- MOBILE: vertical timeline --}}
            <ol class="md:hidden flex flex-col gap-0 list-none p-0 m-0">
                @foreach($hiwSteps as $step)
                <li class="flex gap-5">
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-10 h-10 bg-brand-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-gray-50 shrink-0">
                            {{ $loop->iteration }}
                        </div>
                        @if(!$loop->last)
                        <div class="w-px flex-1 bg-brand-200 my-1"></div>
                        @endif
                    </div>
                    <div class="{{ $loop->last ? 'pb-0' : 'pb-5' }} flex-1 pt-1">
                        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $step->step_text }}</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ol>

        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════
     CTA
══════════════════════════════════════════════════════════════════ --}}
@if(($s['partner_cta_enabled'] ?? '1') == '1')
<section class="py-20 md:py-24 bg-gradient-to-b from-white to-brand-50 bg-brand-50" id="apply">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto text-center bg-white border border-gray-200 rounded-2xl shadow-2xl shadow-gray-900/[0.12] p-10 md:p-14">

            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mb-5">{{ $s['partner_cta_title'] }}</h2>

            <blockquote class="mb-10">
                <p class="text-gray-500 text-base sm:text-lg italic leading-[1.9] max-w-xl mx-auto">&ldquo;{{ $s['partner_cta_quote'] }}&rdquo;</p>
            </blockquote>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ $s['partner_cta_btn1_link'] }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center btn-primary py-4 px-10 rounded-xl font-bold text-base shadow-md shadow-brand-600/25 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-brand-600/30 transition-all duration-200">
                    {{ $s['partner_cta_btn1_label'] }}
                </a>
                <a href="{{ $s['partner_cta_btn2_link'] }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center btn-secondary py-4 px-10 rounded-xl font-bold text-base shadow-md shadow-brand-600/25 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-brand-600/30 transition-all duration-200">
                    {{ $s['partner_cta_btn2_label'] }}
                </a>
            </div>
        </div>
    </div>
</section>

@endif

@endsection
