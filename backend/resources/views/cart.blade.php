@extends('layouts.app')

@section('meta_title', 'Your Cart | DealMindanao')
@section('meta_description', 'Review your selected deals before checkout.')

@section('content')
<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Your Cart</h1>
        <p class="text-sm text-gray-500">Review your selected deals before checkout.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert-success mb-4">{{ session('success') }}</div>
@endif

@php
    $isLoading = $isLoading ?? false;
@endphp

{{-- LOADING SKELETON --}}
@if($isLoading)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        @for ($i = 1; $i <= 3; $i++)
            <div class="card animate-pulse">
                <div class="h-40 bg-gray-200 rounded mb-3"></div>
                <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-1/2"></div>
            </div>
        @endfor
    </div>

{{-- EMPTY STATE --}}
@elseif(empty($cartItems))
    <div class="card text-center p-10">
        <div class="text-5xl mb-3">🛍️</div>
        <h2 class="text-xl font-semibold mb-2">Your cart is empty</h2>
        <p class="text-gray-500 mb-4">
            Browse our shop and add deals to your cart.
        </p>
        <a href="/shop" class="btn-primary">
            Browse Shop Deals
        </a>
    </div>

{{-- CART ITEMS --}}
@else
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th class="text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium py-4">{{ $item['name'] }}</td>
                        <td class="py-4 text-brand font-semibold">₱{{ number_format($item['price'], 2) }}</td>
                        <td class="py-4">
                            <form action="/cart/update/{{ $item['id'] }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input name="quantity" type="number" min="1" value="{{ $item['quantity'] }}" class="input w-20">
                                <button class="btn-secondary" type="submit">Update</button>
                            </form>
                        </td>
                        <td class="py-4 text-brand font-semibold">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                        <td class="text-right pr-4 py-4">
                            <form action="/cart/remove/{{ $item['id'] }}" method="POST">
                                @csrf
                                <button class="btn-danger" type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card mt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold text-brand">₱{{ number_format($total, 2) }}</p>
        </div>
        <a href="/checkout" class="btn-primary">
            Proceed to Checkout
        </a>
    </div>
@endif
@endsection