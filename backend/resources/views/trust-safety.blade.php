@extends('layouts.app')

@section('meta_title', $s['ts_meta_title'] ?? 'Trust & Safety – DealMindanao | Safe Shopping in Mindanao')
@section('meta_description', $s['ts_meta_description'] ?? 'Discover how DealMindanao keeps you safe. All sellers are verified, orders are reviewed manually, and payment is only collected offline after our team confirms your request.')
@section('meta_keywords', $s['ts_meta_keywords'] ?? 'DealMindanao trust safety, safe shopping Mindanao, verified sellers Philippines, offline payment COD')
@section('canonical', $s['ts_canonical'] ?: 'https://dealmindanao.com/trust-safety')
@section('og_url', 'https://dealmindanao.com/trust-safety')

@push('styles')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {"@@type": "ListItem", "position": 1, "name": "Home", "item": "https://dealmindanao.com"},
    {"@@type": "ListItem", "position": 2, "name": "Trust & Safety", "item": "https://dealmindanao.com/trust-safety"}
  ]
}
</script>
@endpush

@section('content')
<div class="py-24">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">

            {{-- Page Header --}}
            @if(($s['ts_header_enabled'] ?? '1') == '1')
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">{{ $s['ts_title'] }}</h1>
            @if(!empty($s['ts_subtitle']))
            <p class="text-lg text-gray-600 mb-12">{{ $s['ts_subtitle'] }}</p>
            @endif
            @endif

            {{-- Trust & Safety Items --}}
            @if($items->isNotEmpty())
            <div class="space-y-6">
                @foreach($items as $item)
                <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm">
                    <div class="flex items-start gap-4">
                        @if($item->icon_svg)
                        <div class="w-12 h-12 bg-{{ $item->icon_color }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-{{ $item->icon_color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item->icon_svg }}"></path>
                            </svg>
                        </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $item->title }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $item->description }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Bottom Info Section --}}
            @if(($s['ts_footer_enabled'] ?? '1') == '1')
            <div class="mt-12 p-6 bg-brand-50 border border-brand-200 rounded-lg">
                <p class="text-sm text-gray-700 mb-4">
                    @if(!empty($s['ts_footer_prefix'])){{ $s['ts_footer_prefix'] }} @endif
                    @if(!empty($s['ts_footer_contact_url']) && !empty($s['ts_footer_contact_label']))<a href="{{ $s['ts_footer_contact_url'] }}" class="text-brand-600 font-semibold hover:underline">{{ $s['ts_footer_contact_label'] }}</a>@endif
                    @if(!empty($s['ts_footer_suffix'])) {{ $s['ts_footer_suffix'] }}@endif
                </p>
                <div class="flex flex-wrap gap-4 text-sm">
                    @if(!empty($s['ts_footer_link1_label']) && !empty($s['ts_footer_link1_url']))
                    <a href="{{ $s['ts_footer_link1_url'] }}" class="text-brand-600 hover:underline font-semibold">{{ $s['ts_footer_link1_label'] }}</a>
                    @endif
                    @if(!empty($s['ts_footer_link2_label']) && !empty($s['ts_footer_link2_url']))
                    <a href="{{ $s['ts_footer_link2_url'] }}" class="text-brand-600 hover:underline font-semibold">{{ $s['ts_footer_link2_label'] }}</a>
                    @endif
                    @if(!empty($s['ts_footer_link3_label']) && !empty($s['ts_footer_link3_url']))
                    <a href="{{ $s['ts_footer_link3_url'] }}" class="text-brand-600 hover:underline font-semibold">{{ $s['ts_footer_link3_label'] }}</a>
                    @endif
                    @if(!empty($s['ts_footer_link4_label']) && !empty($s['ts_footer_link4_url']))
                    <a href="{{ $s['ts_footer_link4_url'] }}" class="text-brand-600 hover:underline font-semibold">{{ $s['ts_footer_link4_label'] }}</a>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
