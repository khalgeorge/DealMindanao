@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Orders</h1>
        <p class="text-sm text-gray-500">Track new orders and update their status.</p>
    </div>
    <form method="GET" action="/admin/orders" class="flex flex-wrap gap-2">
        <input name="q" value="{{ $search }}" class="input w-56" placeholder="Search order or email">
        <button class="btn-primary" type="submit">Search</button>
    </form>
</div>

@php
    $statusClasses = [
        'pending' => 'badge-warning',
        'contacted' => 'badge-gray',
        'processing' => 'badge-warning',
        'completed' => 'badge-success',
    ];
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap gap-2">
        <a href="/admin/orders" class="btn-secondary">All</a>
        @foreach($statuses as $status)
            <a href="/admin/orders?status={{ $status }}" class="btn-secondary">
                {{ ucfirst($status) }}
            </a>
        @endforeach
    </div>

    @if($orders->count())
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-right pr-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $total = $order->items->sum(function ($item) {
                                return $item->price * $item->quantity;
                            });
                            $badgeClass = $statusClasses[$order->status] ?? 'badge-gray';
                        @endphp
                        <tr>
                            <td class="font-medium py-4">{{ $order->order_number }}</td>
                            <td class="py-4">
                                <div class="font-medium">{{ $order->customer_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->email }}</div>
                            </td>
                            <td class="py-4">{{ $order->items->sum('quantity') }}</td>
                            <td class="py-4 text-brand font-semibold">₱{{ number_format($total, 2) }}</td>
                            <td class="py-4">
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="text-right pr-4 py-4">
                                <div class="flex flex-wrap gap-2 justify-end">
                                    <a href="/admin/orders/{{ $order->id }}" class="btn-secondary">View</a>
                                    <form action="/admin/orders/{{ $order->id }}/status" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="contacted">
                                        <button class="btn-secondary" type="submit" @disabled($order->status === 'contacted')>
                                            Contacted
                                        </button>
                                    </form>
                                    <form action="/admin/orders/{{ $order->id }}/status" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="processing">
                                        <button class="btn-secondary" type="submit" @disabled($order->status === 'processing')>
                                            Processing
                                        </button>
                                    </form>
                                    <form action="/admin/orders/{{ $order->id }}/status" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button class="btn-secondary" type="submit" @disabled($order->status === 'completed')>
                                            Completed
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $orders->links() }}
        </div>
    @else
        <div class="card text-center p-10">
            <div class="text-5xl mb-3">📭</div>
            <h2 class="text-xl font-semibold mb-2">No orders yet</h2>
            <p class="text-gray-500">
                New orders will appear here once customers place requests.
            </p>
        </div>
    @endif
</div>

@endsection
