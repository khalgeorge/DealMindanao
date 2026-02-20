@extends('layouts.app')

@section('title', ($s['tos_title'] ?? 'Terms of Service') . ' - DealMindanao')

@section('content')
<div class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">

            {{-- Header --}}
            @if(($s['tos_header_enabled'] ?? '1') == '1')
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                {{ $s['tos_title'] ?? 'Terms of Service' }}
            </h1>
            @if(!empty($s['tos_subtitle']))
            <p class="text-lg text-gray-600 mb-12">{{ $s['tos_subtitle'] }}</p>
            @endif
            @endif

            {{-- Sections --}}
            @if($sections->isNotEmpty())
            <div class="space-y-8">
                @foreach($sections as $section)
                <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $loop->iteration }}. {{ $section->title }}</h2>
                    {!! $section->body !!}
                </div>
                @endforeach
            </div>
            @endif

            {{-- Footer info bar --}}
            @if(($s['tos_footer_enabled'] ?? '1') == '1')
            <div class="mt-12 p-6 bg-brand-50 border border-brand-200 rounded-lg">
                <p class="text-sm text-gray-700">
                    {{ $s['tos_footer_text'] ?? '' }}
                    @if(!empty($s['tos_footer_link_url']) && !empty($s['tos_footer_link_label']))
                    <a href="{{ $s['tos_footer_link_url'] }}" class="text-brand-600 font-semibold hover:underline">
                        {{ $s['tos_footer_link_label'] }}</a>.
                    @endif
                </p>
            </div>
            @endif

            {{-- Last updated --}}
            @if(!empty($s['tos_last_updated']))
            <p class="mt-8 text-sm text-gray-500">Last updated: {{ $s['tos_last_updated'] }}</p>
            @endif

        </div>
    </div>
</div>
@endsection
