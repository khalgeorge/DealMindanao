@extends('layouts.app')

@section('content')
<div class="py-24">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">{{ $page->title }}</h1>
            @if($page->subtitle)
            <p class="text-lg text-gray-600 mb-12">{{ $page->subtitle }}</p>
            @endif
            <div class="space-y-8">
                @if($page->body)
                    {!! $page->body !!}
                @else
                    <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm text-center">
                        <p class="text-gray-400 font-medium">Content coming soon.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
