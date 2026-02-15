@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<header class="admin-header">
  <div>
    <h1 class="text-xl font-black text-gray-900">Dashboard</h1>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Store Performance Overview</p>
  </div>
  <div class="flex items-center gap-6">
    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-green-50 border border-green-100 rounded-full">
       <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
       <span class="text-[10px] font-black text-green-700 uppercase tracking-tighter">Live System</span>
    </div>
    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
      @csrf
      <button type="submit" class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-red-600 transition-colors">
        <span>Sign Out</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
      </button>
    </form>
  </div>
</header>

<div class="admin-content">
  <!-- Welcome Header -->
  <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
       <h2 class="text-3xl font-black text-gray-900">Welcome back, {{ auth()->user()->name }}</h2>
       <p class="text-gray-500 mt-1">Here is what is happening with DealMindanao today.</p>
    </div>
    <div class="flex gap-2">
       <a href="{{ route('admin.products.create') }}" class="btn-primary btn-sm px-4">Add Product</a>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Revenue Card -->
    <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Revenue</span>
        <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
      </div>
      <p class="text-3xl font-black text-gray-900 leading-none">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
      <div class="mt-4 flex items-center gap-1.5 text-xs">
        <span class="text-gray-400 font-medium">Total sales</span>
      </div>
    </div>

    <!-- Orders Card -->
    <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Orders</span>
        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
      </div>
      <p class="text-3xl font-black text-gray-900 leading-none">{{ $stats['total_orders'] ?? 0 }}</p>
      <div class="mt-4 flex items-center gap-1.5 text-xs">
        <span class="text-gray-400 font-medium">new today</span>
      </div>
    </div>

    <!-- Partners Card -->
    <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Active Partners</span>
        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
      </div>
      <p class="text-3xl font-black text-gray-900 leading-none">{{ $stats['total_companies'] ?? 0 }}</p>
      <div class="mt-4 flex items-center gap-1.5 text-xs">
        <span class="text-gray-400 font-medium">Marketplace Vendors</span>
      </div>
    </div>

    <!-- Pending Order Card -->
    <div class="bg-white p-6 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex items-center justify-between mb-4">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pending</span>
        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
      </div>
      <p class="text-3xl font-black text-gray-900 leading-none">{{ $stats['pending_orders'] ?? 0 }}</p>
      <div class="mt-4 flex items-center gap-1.5 text-xs">
        <a href="{{ route('admin.orders.index') }}" class="text-amber-600 font-bold underline cursor-pointer">Needs Attention</a>
      </div>
    </div>
  </div>

  <!-- Content Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
     <!-- Recent Activity Table -->
     <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 flex items-center justify-between border-b border-gray-50">
           <h3 class="font-black text-gray-900">Recent Orders</h3>
           <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-brand-600 uppercase tracking-widest hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
           <table class="data-table">
              <thead>
                 <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th>Total</th>
                    <th>Status</th>
                 </tr>
              </thead>
              <tbody>
                @forelse($recentOrders as $order)
                 <tr>
                    <td class="font-mono text-xs">{{ $order->order_number }}</td>
                    <td class="font-bold text-gray-900">{{ $order->user->name ?? $order->customer_name ?? 'Guest' }}</td>
                    <td>{{ $order->shipping_city ?? 'N/A' }}</td>
                    <td class="font-bold">₱{{ number_format($order->total ?? 0, 2) }}</td>
                    <td>
                      @php
                        $statusClasses = [
                          'pending' => 'badge-warning',
                          'processing' => 'badge-info',
                          'shipped' => 'badge-success',
                          'delivered' => 'badge-success',
                          'cancelled' => 'badge-danger'
                        ];
                        $statusClass = $statusClasses[$order->status] ?? 'badge-secondary';
                      @endphp
                      <span class="{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                    </td>
                 </tr>
                @empty
                 <tr>
                    <td colspan="5" class="text-center text-gray-400">No orders yet</td>
                 </tr>
                @endforelse
              </tbody>
           </table>
        </div>
     </div>

     <!-- System Info -->
     <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <h3 class="font-black text-gray-900 mb-6">Stock & Partner Alerts</h3>
        <div class="space-y-4">
           <!-- Low stock item -->
           <div class="flex items-center gap-4 p-4 bg-red-50 rounded-lg border border-red-100">
              <div class="w-10 h-10 rounded-lg bg-white border border-red-100 flex items-center justify-center text-red-600 font-bold shadow-sm">!</div>
              <div class="flex-1">
                 <p class="text-sm font-bold text-red-900">Low Stock Alert</p>
                 <p class="text-xs text-red-700">Some products may need restocking soon.</p>
              </div>
              <a href="{{ route('admin.products.index') }}" class="btn-danger btn-sm px-4">View Products</a>
           </div>
           
           <!-- Partner info -->
           <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
              <div class="w-10 h-10 rounded-lg bg-white border border-blue-100 flex items-center justify-center text-blue-600 font-bold shadow-sm">P</div>
              <div class="flex-1">
                 <p class="text-sm font-bold text-blue-900">Partner Companies</p>
                 <p class="text-xs text-blue-700">{{ $stats['total_companies'] ?? 0 }} active vendors on the marketplace.</p>
              </div>
              <a href="{{ route('admin.companies.index') }}" class="btn-primary btn-sm px-4">Manage</a>
           </div>
        </div>
     </div>
  </div>
</div>
@endsection
