@extends('layouts.app')

@php
    $logoUrl = $page->logo_path ? \Illuminate\Support\Facades\Storage::url($page->logo_path) : null;
    $heroUrl = $page->hero_image_path ? \Illuminate\Support\Facades\Storage::url($page->hero_image_path) : null;
    $metaTitle = $page->meta_title ?: ($page->title . ' | DealMindanao');
    $metaDescription = $page->meta_description
        ?: \Illuminate\Support\Str::limit($page->subtitle ?: ($page->body ?? ''), 160);
    $metaImage = $heroUrl ?: $logoUrl;
@endphp

@section('meta_title', $metaTitle)
@section('meta_description', $metaDescription)
@if($metaImage)
    @section('meta_image', $metaImage)
@endif

@section('content')

<div class="grid lg:grid-cols-[1.2fr_1fr] gap-8 items-start">
    <div class="card">
        @if($heroUrl)
            <img src="{{ $heroUrl }}" alt="{{ $page->title }}" class="w-full h-56 object-cover rounded-xl mb-6">
        @endif

        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $page->title }}</h1>
        @if($page->subtitle)
            <p class="text-sm text-gray-500 mb-4">{{ $page->subtitle }}</p>
        @endif

        <div class="text-gray-700 leading-relaxed space-y-4">
            {!! nl2br(e($page->body ?? 'Content coming soon.')) !!}
        </div>
    </div>

    <div class="card">
        <h2 class="text-lg font-semibold mb-3">Branding</h2>
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $page->title }} logo" class="w-32 h-32 object-contain bg-white rounded-xl border border-gray-100 p-3">
        @else
            <p class="text-sm text-gray-500">No logo uploaded.</p>
        @endif
    </div>
</div>
@endsection
