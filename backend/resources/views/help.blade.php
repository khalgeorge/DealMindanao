@extends('layouts.app')

@section('meta_title', $s['help_meta_title'] ?? 'Help Center & FAQ – DealMindanao | Orders, Payment & Delivery')
@section('meta_description', $s['help_meta_description'] ?? 'Find answers about placing orders, offline payment via COD or GCash, regional delivery in Mindanao, and how to request a refund on DealMindanao.')
@section('meta_keywords', $s['help_meta_keywords'] ?? 'DealMindanao FAQ, how to order Mindanao, COD delivery Philippines, GCash payment Mindanao')
@section('canonical', $s['help_canonical'] ?: 'https://dealmindanao.com/help')

@push('styles')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [
    {
      "@@type": "Question",
      "name": "How does payment work?",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "After placing your order, our team will contact you to arrange offline payment such as COD, bank transfer, or GCash."
      }
    },
    {
      "@@type": "Question",
      "name": "How long does delivery take?",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "Delivery typically takes 3\u20137 business days within Mindanao."
      }
    }
  ]
}
</script>
@endpush

@section('content')
<div class="py-24">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">

            {{-- Page Header --}}
            @if(($s['help_header_enabled'] ?? '1') == '1')
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">{{ $s['help_title'] }}</h1>
            <h2 class="text-2xl font-bold mt-10 mb-4">Frequently Asked Questions</h2>
            @if(!empty($s['help_subtitle']))
            <p class="text-lg text-gray-600 mb-12">{{ $s['help_subtitle'] }}</p>
            @endif
            @endif

            {{-- FAQ List --}}
            @if($faqs->isNotEmpty())
            <div class="space-y-6">
                @foreach($faqs as $faq)
                <div class="bg-white p-8 rounded-lg border border-gray-100 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $faq->question }}</h3>
                    <div class="text-gray-600 leading-relaxed">{!! $faq->answer !!}</div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Bottom CTA --}}
            @if(($s['help_cta_enabled'] ?? '1') == '1')
            <div class="mt-12 p-8 bg-brand-50 border-2 border-brand-200 rounded-lg text-center">
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $s['help_cta_title'] }}</h3>
                <p class="text-gray-600 mb-6">{{ $s['help_cta_description'] }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-6">
                    <a href="{{ $s['help_cta_btn1_link'] }}" class="btn-primary">{{ $s['help_cta_btn1_label'] }}</a>
                    <a href="{{ $s['help_cta_btn2_link'] }}" class="btn-secondary">{{ $s['help_cta_btn2_label'] }}</a>
                </div>
                <div class="flex flex-wrap gap-4 justify-center text-sm">
                    <a href="{{ url('/trust-safety') }}" class="text-brand-600 hover:underline font-semibold">Trust &amp; Safety &rarr;</a>
                    <a href="{{ url('/privacy') }}" class="text-brand-600 hover:underline font-semibold">Privacy Policy &rarr;</a>
                    <a href="{{ url('/terms') }}" class="text-brand-600 hover:underline font-semibold">Terms of Service &rarr;</a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
