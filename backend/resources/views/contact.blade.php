@extends('layouts.app')

@section('meta_title', $s['contact_meta_title'] ?? 'Contact DealMindanao – Order Support & Customer Inquiries')
@section('meta_description', $s['contact_meta_description'] ?? 'Reach the DealMindanao team for order inquiries, payment confirmation, or partnership questions. Proudly serving buyers across all Mindanao regions.')
@section('meta_keywords', $s['contact_meta_keywords'] ?? 'contact DealMindanao, order support Mindanao, customer service Davao, Mindanao marketplace help')
@section('canonical', $s['contact_canonical'] ?: url('/contact'))

@push('styles')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "LocalBusiness",
    "name": "DealMindanao",
    "url": "https://dealmindanao.com",
    "logo": "https://dealmindanao.com/logo_main-final.png",
    "image": "https://dealmindanao.com/logo_main-final.png",
    "description": "{{ $s['contact_meta_description'] ?? 'Authentic Mindanao products marketplace.' }}",
    "email": "{{ $s['contact_email'] ?? 'hello@dealmindanao.com' }}",
    "address": {
        "@@type": "PostalAddress",
        "streetAddress": "{{ $s['contact_address'] ?? 'Davao City' }}",
        "addressLocality": "Davao City",
        "addressRegion": "Davao del Sur",
        "addressCountry": "PH"
    },
    "contactPoint": {
        "@@type": "ContactPoint",
        "contactType": "customer support",
        "email": "{{ $s['contact_email'] ?? 'hello@dealmindanao.com' }}",
        "availableLanguage": ["English", "Filipino"]
    },
    "sameAs": [
        "https://www.facebook.com/dealmindanao"
    ]
}
</script>
@endpush

@section('content')
    <div class="container mx-auto px-6 py-20">
        <div class="max-w-6xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-20">

                {{-- Left: Info --}}
                <div>
                    @if(!empty($s['contact_badge']))
                    <span class="text-brand-600 font-black uppercase tracking-[0.2em] text-[10px]">{{ $s['contact_badge'] }}</span>
                    @endif
                    <h1 class="text-5xl font-black text-gray-900 mt-4 mb-8">
                        {{ $s['contact_title'] }} <span class="text-brand-600">{{ $s['contact_title_highlight'] }}</span>
                    </h1>
                    <p class="text-lg text-gray-600 font-medium leading-relaxed mb-12">{{ $s['contact_description'] }}</p>

                    <div class="space-y-10">
                        <div class="flex gap-6">
                            <div class="w-14 h-14 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center shrink-0" aria-hidden="true">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-1">Email Support</h4>
                                <p class="text-gray-500 font-medium text-lg">{{ $s['contact_email'] }}</p>
                            </div>
                        </div>
                        <div class="flex gap-6">
                            <div class="w-14 h-14 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center shrink-0" aria-hidden="true">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-1">Davao Office</h4>
                                <p class="text-gray-500 font-medium text-lg">{{ $s['contact_address'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 p-6 bg-brand-50 rounded-lg border border-brand-200">
                        <h3 class="font-bold text-gray-900 mb-3 text-sm">Quick Links</h3>
                        <div class="flex flex-wrap gap-3 text-sm">
                            <a href="{{ url('/help') }}" class="text-brand-600 hover:underline font-semibold">Help Center &rarr;</a>
                            <a href="{{ url('/trust-safety') }}" class="text-brand-600 hover:underline font-semibold">Trust &amp; Safety &rarr;</a>
                            <a href="{{ url('/refunds') }}" class="text-brand-600 hover:underline font-semibold">Refunds &amp; Returns &rarr;</a>
                        </div>
                    </div>
                </div>

                {{-- Right: Form --}}
                <div class="bg-gray-50 p-12 rounded-lg border border-gray-100 shadow-inner">
                    <form method="POST" action="{{ url('/contact/send') }}" name="contact" class="space-y-6" novalidate>
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="contact_name" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
                                <input type="text" id="contact_name" name="name" required autocomplete="name"
                                       class="input w-full py-4 rounded-lg border-transparent bg-white shadow-sm"
                                       placeholder="Juan Dela Cruz">
                            </div>
                            <div>
                                <label for="contact_subject" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Subject</label>
                                <select id="contact_subject" name="subject" required
                                        class="input w-full py-4 rounded-lg border-transparent bg-white shadow-sm">
                                    <option value="">Select a subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="order_status">Order Status &amp; Confirmation</option>
                                    <option value="payment_delivery">Payment &amp; Delivery Questions</option>
                                    <option value="returns">Returns &amp; Refunds</option>
                                    <option value="partner">Become a Partner</option>
                                    <option value="report">Report an Issue</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="contact_message" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Message</label>
                            <textarea id="contact_message" name="message" required
                                      class="input w-full py-4 rounded-lg border-transparent bg-white shadow-sm h-40"
                                      placeholder="How can we help?"></textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full py-5 rounded-lg font-black uppercase tracking-widest shadow-xl shadow-brand-100">
                            Send Message
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
