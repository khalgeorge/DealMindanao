@extends('layouts.app')

@section('meta_title', $s['rp_meta_title'] ?? 'Refund & Returns Policy – DealMindanao | Mindanao Marketplace')
@section('meta_description', $s['rp_meta_description'] ?? 'Learn how to request a refund or return on DealMindanao. Understand our policy for GCash and Bank Transfer orders from verified Mindanao sellers.')
@section('meta_keywords', $s['rp_meta_keywords'] ?? 'DealMindanao refund policy, returns Mindanao, GCash order return, Bank Transfer refund Philippines')
@section('canonical', $s['rp_canonical'] ?: 'https://dealmindanao.com/refunds')

@push('styles')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {"@@type": "ListItem", "position": 1, "name": "Home", "item": "https://dealmindanao.com"},
    {"@@type": "ListItem", "position": 2, "name": "Refund & Returns Policy", "item": "https://dealmindanao.com/refunds"}
  ]
}
</script>
@endpush

@section('content')
<div class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">

            {{-- Header --}}
            @if(($s['rp_header_enabled'] ?? '1') == '1')
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                {{ $s['rp_title'] ?? 'Refund &amp; Returns Policy' }}
            </h1>
            @if(!empty($s['rp_subtitle']))
            <p class="text-lg text-gray-600 mb-12">{{ $s['rp_subtitle'] }}</p>
            @endif
            @endif

            {{-- Sections --}}
            @if($sections->isNotEmpty())
            <div class="space-y-8">
                @foreach($sections as $section)
                <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $section->title }}</h2>
                    {!! $section->body !!}
                </div>
                @endforeach
            </div>
            @endif

            {{-- Footer info bar --}}
            @if(($s['rp_footer_enabled'] ?? '1') == '1')
            <div class="mt-12 p-6 bg-brand-50 border border-brand-200 rounded-lg">
                <p class="text-sm text-gray-700">
                    {{ $s['rp_footer_text'] ?? '' }}
                    @if(!empty($s['rp_footer_link_url']) && !empty($s['rp_footer_link_label']))
                    <a href="{{ $s['rp_footer_link_url'] }}" class="text-brand-600 font-semibold hover:underline">
                        {{ $s['rp_footer_link_label'] }}</a>.
                    @endif
                </p>
            </div>
            @endif

            {{-- Last updated --}}
            @if(!empty($s['rp_last_updated']))
            <p class="mt-8 text-sm text-gray-500">Last updated: {{ $s['rp_last_updated'] }}</p>
            @endif

        </div>
    </div>
</div>
@endsection
