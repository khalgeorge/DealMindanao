@extends('layouts.app')

@section('meta_title', $s['pp_meta_title'] ?? 'Privacy Policy – DealMindanao | How We Protect Your Data')
@section('meta_description', $s['pp_meta_description'] ?? 'Read the DealMindanao Privacy Policy to understand how your personal information is collected, used, and protected when you place orders on our Mindanao marketplace.')
@section('meta_keywords', $s['pp_meta_keywords'] ?? 'DealMindanao privacy policy, data protection Philippines, personal information Mindanao marketplace')
@section('canonical', $s['pp_canonical'] ?: url('/privacy'))

@section('content')
<div class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">

            {{-- Page Header --}}
            @if(($s['pp_header_enabled'] ?? '1') == '1')
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">{{ $s['pp_title'] }}</h1>
            @if(!empty($s['pp_subtitle']))
            <p class="text-lg text-gray-600 mb-12">{{ $s['pp_subtitle'] }}</p>
            @endif
            @endif

            {{-- Policy Sections --}}
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

            {{-- Bottom Info Bar --}}
            @if(($s['pp_footer_enabled'] ?? '1') == '1')
            <div class="mt-12 p-6 bg-brand-50 border border-brand-200 rounded-lg">
                <p class="text-sm text-gray-700">
                    @if(!empty($s['pp_footer_text'])){{ $s['pp_footer_text'] }} @endif
                    @if(!empty($s['pp_footer_link_url']) && !empty($s['pp_footer_link_label']))<a href="{{ $s['pp_footer_link_url'] }}" class="text-brand-600 font-semibold hover:underline">{{ $s['pp_footer_link_label'] }}</a>.@endif
                </p>
            </div>
            @endif

            {{-- Last Updated --}}
            @if(!empty($s['pp_last_updated']))
            <p class="mt-8 text-sm text-gray-500">Last updated: {{ $s['pp_last_updated'] }}</p>
            @endif

        </div>
    </div>
</div>
@endsection
