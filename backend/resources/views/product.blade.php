@extends('layouts.app')

@php
    $imageUrl = $product->images && count($product->images)
        ? \Illuminate\Support\Facades\Storage::url($product->images[0])
        : null;
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
<div class="grid lg:grid-cols-2 gap-8">
    <div class="card">
        <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
            @else
                <div class="flex h-full w-full items-center justify-center text-sm text-gray-400">
                    No image available
                </div>
            @endif
        </div>
    </div>

    <div class="space-y-4">
        <div>
            @if($product->discount)
                <span class="badge-warning ml-2">Promo</span>
            @endif
            <h1 class="text-3xl font-bold mt-2">{{ $product->name }}</h1>
            <p class="text-gray-500">by {{ $product->company->name ?? 'Partner' }}</p>
        </div>

        <p class="text-lg text-gray-700">
            {{ $product->description ?? 'No description yet.' }}
        </p>

        <div class="flex items-center gap-4">
            <p class="text-brand font-bold text-2xl">₱{{ number_format($product->price, 2) }}</p>
            @if($product->discount)
                <span class="pill-accent">Limited offer</span>
            @endif
        </div>

        <form action="/cart/add/{{ $product->id }}" method="POST" class="flex flex-wrap gap-3">
            @csrf
            <button class="btn-primary" type="submit">Add to Cart</button>
            <a href="/shop" class="btn-secondary">Back to Shop</a>
        </form>
    </div>
</div>
@endsection