@extends('layouts.app')

@section('meta_title', $s['about_meta_title'] ?? 'About DealMindanao - Authentic Mindanao Hardware & Heavy Equipment')
@section('meta_description', $s['about_meta_description'] ?? 'DealMindanao is a curated online marketplace connecting customers with quality hardware and heavy equipment.')
@section('meta_keywords', $s['about_meta_keywords'] ?? 'DealMindanao, about, Mindanao marketplace')
@section('canonical', $s['about_canonical'] ?: url('/about'))

@section('content')

{{-- Hero --}}
<section class="pt-32 pb-20 bg-brand-50">
    <div class="container mx-auto px-6 text-center max-w-4xl">
        <h1 class="text-5xl lg:text-7xl font-black text-gray-900 mb-8">{{ $s['about_hero_title'] }} <span class="text-brand-600">{{ $s['about_hero_title_highlight'] }}</span></h1>
        <p class="text-xl text-gray-600 font-medium leading-relaxed">{{ $s['about_hero_subtitle'] }}</p>
    </div>
</section>

{{-- Core Values --}}
<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-3 gap-12">
            <div class="p-10 bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-xl transition-shadow">
                <div class="w-14 h-14 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center mb-6 font-black text-xl">01</div>
                <h3 class="font-black text-xl mb-4 uppercase tracking-tighter">{{ $s['about_card1_title'] }}</h3>
                <p class="text-gray-500 font-medium leading-relaxed">{{ $s['about_card1_description'] }}</p>
            </div>
            <div class="p-10 bg-brand-600 rounded-lg text-white shadow-xl">
                <div class="w-14 h-14 bg-white/20 text-white rounded-lg flex items-center justify-center mb-6 font-black text-xl">02</div>
                <h3 class="font-black text-xl mb-4 uppercase tracking-tighter">{{ $s['about_card2_title'] }}</h3>
                <p class="text-brand-100 font-medium leading-relaxed">{{ $s['about_card2_description'] }}</p>
            </div>
            <div class="p-10 bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-xl transition-shadow">
                <div class="w-14 h-14 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center mb-6 font-black text-xl">03</div>
                <h3 class="font-black text-xl mb-4 uppercase tracking-tighter">{{ $s['about_card3_title'] }}</h3>
                <p class="text-gray-500 font-medium leading-relaxed">{{ $s['about_card3_description'] }}</p>
            </div>
        </div>
    </div>
</section>

{{-- Story Section --}}
@php
    $storyImg = $s['about_image'];
    if ($storyImg && !str_starts_with($storyImg, '/') && !str_starts_with($storyImg, 'http')) {
        $storyImg = Storage::url($storyImg);
    }
@endphp
<section class="py-24 bg-gray-50 overflow-hidden">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-20 items-center">
            <div class="relative">
                <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden shadow-2xl relative z-10">
                    <img src="{{ $storyImg }}" class="w-full h-full object-cover" alt="Mindanao Story">
                </div>
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-brand-600 rounded-lg -z-0"></div>
            </div>
            <div>
                <h2 class="text-4xl font-black text-gray-900 mb-8 leading-tight">{{ $s['about_section_title'] }} <span class="bg-brand-100 px-2 italic text-brand-600">{{ $s['about_section_title_highlight'] }}</span></h2>
                <div class="space-y-6 text-gray-500 font-medium leading-relaxed">
                    <p>{{ $s['about_story_paragraph1'] }}</p>
                    <p>{{ $s['about_story_paragraph2'] }}</p>
                </div>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ $s['about_cta1_link'] }}" class="btn-primary">{{ $s['about_cta1_label'] }}</a>
                    <a href="{{ $s['about_cta2_link'] }}" class="btn-secondary">{{ $s['about_cta2_label'] }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
