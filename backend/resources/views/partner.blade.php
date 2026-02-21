@extends('layouts.app')

@section('meta_title', $s['partner_meta_title'] ?? 'Become a Partner - DealMindanao')
@section('meta_description', $s['partner_meta_description'] ?? 'Partner with DealMindanao to showcase your products to more customers online.')
@section('meta_keywords', $s['partner_meta_keywords'] ?? 'DealMindanao partner, sell online')
@section('canonical', $s['partner_canonical'] ?: url('/partner'))

@section('content')

{{-- Hero --}}
@if(($s['partner_hero_enabled'] ?? '1') == '1')
<section class="pt-32 pb-24 relative overflow-hidden bg-gray-900 text-white">
    <div class="absolute inset-0 bg-brand-600 opacity-20 mix-blend-multiply"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        @if(!empty($s['partner_hero_badge']))
        <span class="inline-block py-1 px-3 bg-brand-600 text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6">{{ $s['partner_hero_badge'] }}</span>
        @endif
        <h1 class="text-5xl lg:text-7xl font-black mb-8">
            <span style="color: white;">{{ $s['partner_hero_title'] }}</span>
            <span class="text-brand-400"> {{ $s['partner_hero_title_highlight'] }}</span>
        </h1>
        <p class="text-xl text-gray-300 font-medium max-w-3xl mx-auto leading-relaxed mb-12">{{ $s['partner_hero_description'] }}</p>
        <a href="{{ $s['partner_hero_cta_link'] }}" class="btn-primary px-12 py-5 rounded-lg text-lg font-black uppercase tracking-widest shadow-2xl">{{ $s['partner_hero_cta_label'] }}</a>
    </div>
</section>
@endif

{{-- Features --}}
@if(($s['partner_features_enabled'] ?? '1') == '1')
<section class="py-24">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-3 gap-16">
            @if(($s['partner_card1_enabled'] ?? '1') == '1')
            <div class="text-center">
                <div class="w-16 h-16 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center mx-auto mb-8 font-black text-2xl shadow-lg shadow-brand-100 italic">{{ $s['partner_card1_number'] }}</div>
                <h3 class="font-black text-xl mb-4 text-gray-900 uppercase tracking-tighter">{{ $s['partner_card1_title'] }}</h3>
                <p class="text-gray-500 font-medium">{{ $s['partner_card1_description'] }}</p>
            </div>
            @endif
            @if(($s['partner_card2_enabled'] ?? '1') == '1')
            <div class="text-center">
                <div class="w-16 h-16 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center mx-auto mb-8 font-black text-2xl shadow-lg shadow-brand-100 italic">{{ $s['partner_card2_number'] }}</div>
                <h3 class="font-black text-xl mb-4 text-gray-900 uppercase tracking-tighter">{{ $s['partner_card2_title'] }}</h3>
                <p class="text-gray-500 font-medium">{{ $s['partner_card2_description'] }}</p>
            </div>
            @endif
            @if(($s['partner_card3_enabled'] ?? '1') == '1')
            <div class="text-center">
                <div class="w-16 h-16 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center mx-auto mb-8 font-black text-2xl shadow-lg shadow-brand-100 italic">{{ $s['partner_card3_number'] }}</div>
                <h3 class="font-black text-xl mb-4 text-gray-900 uppercase tracking-tighter">{{ $s['partner_card3_title'] }}</h3>
                <p class="text-gray-500 font-medium">{{ $s['partner_card3_description'] }}</p>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
@if(($s['partner_cta_enabled'] ?? '1') == '1')
<section class="py-24 bg-brand-50" id="apply">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto bg-white p-16 rounded-lg shadow-2xl shadow-brand-100 text-center">
            <h2 class="text-4xl font-black text-gray-900 mb-6">{{ $s['partner_cta_title'] }}</h2>
            <p class="text-gray-500 font-medium text-lg mb-10 leading-relaxed italic">{{ $s['partner_cta_quote'] }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ $s['partner_cta_btn1_link'] }}" class="btn-primary py-5 px-12 rounded-lg font-black uppercase tracking-widest">{{ $s['partner_cta_btn1_label'] }}</a>
                <a href="{{ $s['partner_cta_btn2_link'] }}" class="btn-secondary py-5 px-12 rounded-lg font-black uppercase tracking-widest border-gray-100">{{ $s['partner_cta_btn2_label'] }}</a>
            </div>
        </div>
    </div>
</section>
@endif

@endsection
