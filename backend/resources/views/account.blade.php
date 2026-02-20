@extends('layouts.app')

@section('meta_title', 'My Account | DealMindanao')
@section('meta_description', 'Manage your orders and account information.')

@section('content')
<div class="page-shell py-8">

  {{-- Header --}}
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">My Account</h1>
    <p class="text-gray-600 mt-2">Manage your orders and account information</p>
  </div>

  {{-- Account Info Card --}}
  <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Account Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <p class="text-gray-900">{{ $user->name }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <p class="text-gray-900">{{ $user->email }}</p>
      </div>
    </div>
    <div class="mt-6 flex gap-3">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-outline">Logout</button>
      </form>
    </div>
  </div>

  {{-- Orders Section --}}
  <div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">My Orders</h2>

    @if($orders->isEmpty())
      {{-- Empty State --}}
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
        <p class="text-gray-600 mb-6">Start shopping to see your orders here</p>
        <a href="{{ route('shop') }}" class="btn-primary inline-block">Browse Products</a>
      </div>
    @else
      {{-- Orders List --}}
      <div class="space-y-4">
        @foreach($orders as $order)
          <div class="border border-gray-200 rounded-lg p-5">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
              <div>
                <p class="text-sm font-bold text-gray-900">{{ $order->order_number }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('F j, Y') }}</p>
              </div>
              <div class="flex items-center gap-3">
                @php
                  $statusMap = [
                    'pending'    => ['bg-yellow-100 text-yellow-800', 'Pending'],
                    'processing' => ['bg-blue-100 text-blue-800',   'Processing'],
                    'shipped'    => ['bg-purple-100 text-purple-800','Shipped'],
                    'delivered'  => ['bg-green-100 text-green-800',  'Delivered'],
                    'cancelled'  => ['bg-red-100 text-red-800',      'Cancelled'],
                  ];
                  [$badge, $label] = $statusMap[$order->status] ?? ['bg-gray-100 text-gray-800', ucfirst($order->status)];
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                  {{ $label }}
                </span>
                <span class="text-sm font-bold text-brand-600">
                  ₱{{ number_format($order->total, 2) }}
                </span>
              </div>
            </div>

            {{-- Order Items --}}
            <div class="space-y-2">
              @foreach($order->items as $item)
                <div class="flex items-center justify-between text-sm">
                  <span class="text-gray-700">
                    {{ $item->product_name }}
                    <span class="text-gray-400">× {{ $item->quantity }}</span>
                  </span>
                  <span class="text-gray-900 font-medium">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
              @endforeach
            </div>

            {{-- Shipping --}}
            <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500">
              <span class="font-medium text-gray-700">Ship to:</span>
              {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }}
            </div>
          </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($orders->hasPages())
        <div class="mt-6">
          {{ $orders->links() }}
        </div>
      @endif
    @endif
  </div>

</div>
@endsection
