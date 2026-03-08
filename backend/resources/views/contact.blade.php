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
@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}" async defer></script>
@endif
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 004 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-1">Online Support (Mindanao-Wide)</h4>
                                <p class="text-gray-500 font-medium">We currently operate online and coordinate directly with verified local sellers and delivery partners across Mindanao.</p>
                                <p class="text-gray-500 font-medium mt-1">Support is available via email and chat.</p>
                                <p class="text-gray-400 text-sm mt-2">Serving Davao, CDO, Zamboanga, Bukidnon, and nearby areas.</p>
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

                    {{-- Success message --}}
                    @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 font-medium text-sm" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    {{-- CAPTCHA error --}}
                    @error('captcha')
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 font-medium text-sm" role="alert">
                        {{ $message }}
                    </div>
                    @enderror

                    <form method="POST" action="{{ url('/contact/send') }}" name="contact" id="contact-form" class="space-y-6" novalidate>
                        @csrf
                        <x-honeypot />

                        {{-- Row 1: Full Name + Subject --}}
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="contact_name" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name <span class="text-red-400">*</span></label>
                                <input type="text" id="contact_name" name="name" required autocomplete="name"
                                       value="{{ old('name') }}"
                                       class="input w-full py-4 rounded-lg border-transparent bg-white shadow-sm @error('name') !border-red-400 @enderror"
                                       placeholder="Juan Dela Cruz"
                                       aria-required="true"
                                       aria-describedby="error-name">
                                @error('name')
                                <p id="error-name" class="mt-1 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact_subject" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Subject <span class="text-red-400">*</span></label>
                                <select id="contact_subject" name="subject" required
                                        class="input w-full py-4 rounded-lg border-transparent bg-white shadow-sm @error('subject') !border-red-400 @enderror"
                                        aria-required="true"
                                        aria-describedby="error-subject">
                                    <option value="">Select a subject</option>
                                    <option value="general" {{ old('subject') === 'general' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="order_status" {{ old('subject') === 'order_status' ? 'selected' : '' }}>Order Status &amp; Confirmation</option>
                                    <option value="payment_delivery" {{ old('subject') === 'payment_delivery' ? 'selected' : '' }}>Payment &amp; Delivery Questions</option>
                                    <option value="returns" {{ old('subject') === 'returns' ? 'selected' : '' }}>Returns &amp; Refunds</option>
                                    <option value="partner" {{ old('subject') === 'partner' ? 'selected' : '' }}>Become a Partner</option>
                                    <option value="report" {{ old('subject') === 'report' ? 'selected' : '' }}>Report an Issue</option>
                                </select>
                                @error('subject')
                                <p id="error-subject" class="mt-1 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Row 2: Email + Contact Number --}}
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="contact_email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address <span class="text-red-400">*</span></label>
                                <input type="email" id="contact_email" name="email" required autocomplete="email"
                                       value="{{ old('email') }}"
                                       class="input w-full py-4 rounded-lg border-transparent bg-white shadow-sm @error('email') !border-red-400 @enderror"
                                       placeholder="juan@email.com"
                                       aria-required="true"
                                       aria-describedby="error-email">
                                @error('email')
                                <p id="error-email" class="mt-1 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="contact_phone" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Contact Number <span class="text-gray-300 normal-case font-normal">(Optional)</span></label>
                                <input type="tel" id="contact_phone" name="phone" autocomplete="tel"
                                       value="{{ old('phone') }}"
                                       class="input w-full py-4 rounded-lg border-transparent bg-white shadow-sm"
                                       placeholder="09XXXXXXXXX">
                            </div>
                        </div>

                        {{-- Row 3: Message --}}
                        <div>
                            <label for="contact_message" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Message <span class="text-red-400">*</span></label>
                            <textarea id="contact_message" name="message" required
                                      class="input w-full py-4 rounded-lg border-transparent bg-white shadow-sm h-40 @error('message') !border-red-400 @enderror"
                                      placeholder="How can we help?"
                                      aria-required="true"
                                      aria-describedby="error-message">{{ old('message') }}</textarea>
                            @error('message')
                            <p id="error-message" class="mt-1 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Data Privacy Consent (RA 10173 / DPA compliance) --}}
                        <div class="p-5 bg-white rounded-lg border border-gray-100 shadow-sm" aria-label="Data privacy consent">
                            <p class="text-xs text-gray-500 leading-relaxed mb-4">
                                By submitting this form, you agree that DealMindanao may collect and process your personal information in accordance with the Data Privacy Act of 2012 (RA&nbsp;10173) and our <a href="{{ url('/privacy') }}" class="text-brand-600 hover:underline font-semibold">Privacy Policy</a>. Your information will only be used to respond to your inquiry and provide support.
                            </p>
                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="privacy_consent" name="privacy_consent" value="1" required
                                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 shrink-0"
                                       aria-required="true"
                                       aria-describedby="error-privacy"
                                       {{ old('privacy_consent') ? 'checked' : '' }}>
                                <label for="privacy_consent" class="text-sm text-gray-700 font-medium leading-snug cursor-pointer">
                                    I agree to the <a href="{{ url('/privacy') }}" class="text-brand-600 hover:underline font-semibold">Data Privacy Policy</a> <span class="text-red-400">*</span>
                                </label>
                            </div>
                            @error('privacy_consent')
                            <p id="error-privacy" class="mt-2 text-xs text-red-500 font-medium" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Hidden reCAPTCHA v3 token --}}
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                        {{-- reCAPTCHA branding (required by Google ToS when badge is hidden) --}}
                        @if(config('services.recaptcha.site_key'))
                        <p class="text-[10px] text-gray-400 text-center">
                            Protected by reCAPTCHA &mdash;
                            <a href="https://policies.google.com/privacy" class="underline hover:text-gray-600" target="_blank" rel="noopener noreferrer">Privacy</a> &amp;
                            <a href="https://policies.google.com/terms" class="underline hover:text-gray-600" target="_blank" rel="noopener noreferrer">Terms</a> apply.
                        </p>
                        @endif

                        <button type="submit" class="btn-primary w-full py-5 rounded-lg font-black uppercase tracking-widest shadow-xl shadow-brand-100">
                            Send Message
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
@if(config('services.recaptcha.site_key'))
<script>
grecaptcha.ready(function () {
    document.getElementById('contact-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = this;
        const btn  = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Verifying…';
        grecaptcha.execute('{{ config("services.recaptcha.site_key") }}', { action: 'contact_submit' })
            .then(function (token) {
                document.getElementById('g-recaptcha-response').value = token;
                form.submit();
            })
            .catch(function () {
                btn.disabled = false;
                btn.textContent = 'Send Message';
            });
    });
});
</script>
@else
<script>
/* reCAPTCHA not configured — form submits directly (set RECAPTCHA_SITE_KEY in .env to enable) */
document.getElementById('contact-form').addEventListener('submit', function () {
    document.getElementById('g-recaptcha-response').value = 'dev-bypass';
});
</script>
@endif
@endpush
