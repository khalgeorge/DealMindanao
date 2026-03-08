@extends('layouts.admin')

@section('content')
@php
    $total = $order->items->sum(function ($item) {
        return $item->price * $item->quantity;
    });
    $statusClasses = [
        'pending' => 'badge-warning',
        'contacted' => 'badge-gray',
        'processing' => 'badge-warning',
        'completed' => 'badge-success',
    ];
    $badgeClass = $statusClasses[$order->status] ?? 'badge-gray';
@endphp

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Order {{ $order->order_number }}</h1>
        <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y g:i A') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('orders.partnerSheet', $order) }}" target="_blank" class="btn-secondary">🖨 Partner Sheet</a>
        <a href="/admin/orders" class="btn-secondary">Back to Orders</a>
    </div>
</div>

<div class="space-y-6">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="grid lg:grid-cols-[1.2fr_1fr] gap-6">
        <div class="card">
            <h2 class="text-lg font-semibold mb-3">Items</h2>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-3">
                                    {{ $item->product_name }}
                                    @if($item->product?->model_code)
                                        <br><span style="font-size:10px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">MODEL: {{ $item->product->model_code }}</span>
                                    @endif
                                    @if($item->variant)
                                        <br><span style="font-size:10px; color:#059669; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">VARIANT: {{ $item->variant }}</span>
                                    @endif
                                </td>
                                <td class="py-3">{{ $item->quantity }}</td>
                                <td class="py-3">₱{{ number_format($item->price, 2) }}</td>
                                <td class="py-3">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-4 text-lg font-semibold">
                Total: ₱{{ number_format($total, 2) }}
            </div>
        </div>

        <div class="space-y-4">
            <div class="card">
                <h2 class="text-lg font-semibold mb-3">Customer</h2>
                <p class="text-sm text-gray-600">Name: {{ $order->customer_name }}</p>
                <p class="text-sm text-gray-600">Email: {{ $order->email }}</p>
                <p class="text-sm text-gray-600">Phone: {{ $order->phone }}</p>
                <p class="text-sm text-gray-600">Address: {{ $order->address }}</p>
            </div>

            <div class="card">
                <h2 class="text-lg font-semibold mb-3">Status</h2>
                <p class="mb-3">
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                </p>
                <form action="/admin/orders/{{ $order->id }}/status" method="POST" class="grid gap-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="select">
                        @foreach(['pending', 'contacted', 'processing', 'completed'] as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @if(!app()->environment('production'))
                        @error('status')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    @endif
                    <button class="btn-primary" type="submit">Update Status</button>
                </form>
            </div>

            @if($order->notes)
                <div class="card">
                    <h2 class="text-lg font-semibold mb-3">Notes</h2>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
