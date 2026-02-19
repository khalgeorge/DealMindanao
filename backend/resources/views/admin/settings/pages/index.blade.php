@extends('layouts.admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Settings</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Platform Parameters</p>
    </div>
</header>

<div class="admin-content">
    @if(session('success'))
        <div class="alert-success mb-6">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1 space-y-2">
            <nav class="flex flex-col space-y-1">
                <a href="{{ route('admin.settings.pages.index') }}"
                   class="flex items-center px-4 py-3 text-sm font-bold rounded-lg bg-white border border-gray-100 text-brand-600 shadow-sm">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Page Content
                </a>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50">
                    <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Page Content</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Update static page titles, body copy and assets</p>
                </div>

                <div class="divide-y divide-gray-50">
                    @foreach($allowed as $slug => $label)
                        @php $page = $pages->get($slug); @endphp
                        <div class="flex items-center justify-between px-8 py-5 hover:bg-gray-50/50 transition-colors">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-black text-gray-900">{{ $label }}</p>
                                <p class="text-xs font-bold text-gray-400 mt-0.5 truncate">
                                    {{ $page?->title ?? 'No title set' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-6 ml-6 shrink-0">
                                <span class="text-xs font-bold text-gray-400 hidden sm:block">
                                    {{ $page?->updated_at?->format('M d, Y') ?? 'Never' }}
                                </span>
                                <a href="{{ route('admin.settings.pages.edit', $slug) }}"
                                   class="text-brand-600 font-bold text-xs uppercase hover:underline">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
