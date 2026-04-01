@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900">Reviews</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Moderate Product & Seller Reviews</p>
    </div>
    @if($pendingCount > 0)
    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 text-xs font-black uppercase tracking-widest">
        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
        {{ $pendingCount }} Pending
    </span>
    @endif
</header>

<div class="admin-content">

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-sm font-semibold text-green-800">{{ session('success') }}</div>
    @endif

    {{-- Tabs --}}
    <div class="mb-8 border-b border-gray-200">
        <nav class="flex gap-8">
            <a href="{{ route('admin.reviews.index') }}?status=pending"
               class="pb-4 px-1 border-b-2 font-bold text-sm transition-all {{ $status === 'pending' ? 'border-brand-600 text-brand-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                Pending
                @if($pendingCount > 0)
                <span class="ml-2 px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-black">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.reviews.index') }}?status=approved"
               class="pb-4 px-1 border-b-2 font-bold text-sm transition-all {{ $status === 'approved' ? 'border-brand-600 text-brand-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                Approved
                <span class="ml-2 px-1.5 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[10px] font-black">{{ $approvedCount }}</span>
            </a>
        </nav>
    </div>

    @if($reviews->isEmpty())
    <div class="text-center py-20 bg-white rounded-lg border border-dashed border-gray-200">
        <div class="text-4xl mb-4">⭐</div>
        <p class="text-gray-500 font-semibold">No {{ $status }} reviews yet.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($reviews as $review)
        @php
            $isProduct  = $review->reviewable_type === \App\Models\Product::class;
            $label      = $isProduct ? 'Product' : 'Seller';
            $name       = $review->reviewable?->name ?? '—';
        @endphp
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 flex gap-6 items-start">

            {{-- Star Rating --}}
            <div class="flex-shrink-0 text-center w-16">
                <div class="text-2xl font-black text-gray-900">{{ $review->rating }}</div>
                <div class="flex justify-center text-amber-400 text-sm leading-none">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= $review->rating ? '★' : '☆' }}
                    @endfor
                </div>
                <span class="text-[9px] uppercase tracking-widest font-black text-gray-400 mt-1 block">{{ $label }}</span>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <span class="text-xs font-black uppercase tracking-widest px-2 py-0.5 rounded {{ $isProduct ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                        {{ $label }}
                    </span>
                    <span class="text-sm font-bold text-gray-900 truncate">{{ $name }}</span>
                    <span class="text-xs text-gray-400">by <span class="font-semibold text-gray-600">{{ $review->user?->name ?? 'Unknown' }}</span></span>
                    <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                </div>

                @if($review->title)
                <p class="font-bold text-gray-800 mb-1">{{ $review->title }}</p>
                @endif

                @if($review->body)
                <p class="text-sm text-gray-600 leading-relaxed">{{ $review->body }}</p>
                @else
                <p class="text-xs text-gray-400 italic">No written review.</p>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex-shrink-0 flex flex-col gap-2">
                @if(!$review->is_approved)
                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-primary btn-sm px-4 whitespace-nowrap">
                        ✓ Approve
                    </button>
                </form>
                @else
                <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest text-center">Live</span>
                @endif

                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                      onsubmit="return confirm('Delete this review?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm px-4 whitespace-nowrap w-full">
                        Delete
                    </button>
                </form>
            </div>

        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($reviews->hasPages())
    <div class="mt-8">
        {{ $reviews->links() }}
    </div>
    @endif
    @endif

</div>
@endsection
