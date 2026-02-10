@extends('layouts.app')

@section('meta_title', 'DealMindanao | Local Deals in Mindanao')
@section('meta_description', 'Discover local deals from trusted Mindanao partners. Order online, pay offline.')

@section('content')
<div class="space-y-10">
    <section class="bg-white rounded-2xl shadow p-8 md:p-12">
        <h1 class="text-4xl font-bold mb-4">
            Discover Local Deals in Mindanao
        </h1>
        <p class="text-xs text-gray-500 mt-2">
            Curated deals from trusted local partners.
            No online payments — order now, pay offline.
        </p>

        <a href="/shop" class="btn-primary inline-block mt-6">
            Browse Shop Deals
        </a>
    </section>

    @if($featuredProducts->count())
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Featured Deals</h2>
                <a href="/shop" class="btn-secondary">View All</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredProducts as $product)
                    @php
                        $imageUrl = $product->images && count($product->images)
                            ? \Illuminate\Support\Facades\Storage::url($product->images[0])
                            : null;
                    @endphp
                    <div class="card card-hover relative">
                        <span class="badge badge-success absolute top-4 left-4">Featured</span>
                        <div class="aspect-[4/3] bg-gray-200 rounded mb-3 overflow-hidden">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @endif
                        </div>

                        <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $product->company->name ?? 'Partner' }}</p>

                        <div class="flex items-center gap-2 mt-2">
                            <p class="font-bold text-brand text-lg">
                                ₱{{ number_format($product->price, 2) }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-3">
                            <a href="/shop/{{ $product->slug }}" class="btn-secondary">View Deal</a>
                            <form action="/cart/add/{{ $product->id }}" method="POST">
                                @csrf
                                <button class="btn-primary" type="submit">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
