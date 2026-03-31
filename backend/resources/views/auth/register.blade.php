@extends('layouts.app')

@section('meta_title', 'Join DealMindanao')
@section('meta_description', 'Create an account to access exclusive local deals from Mindanao.')

@section('content')
<div class="min-h-screen bg-gray-50 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-white via-gray-50 to-gray-100 py-32">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-2xl shadow-brand-100 overflow-hidden flex flex-col md:flex-row border border-gray-100">
            <!-- Side Decor -->
            <div class="md:w-1/2 bg-brand-600 p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-black italic tracking-tighter">DEALMINDANAO</h2>
                    <p class="mt-6 text-brand-100 font-medium leading-relaxed">Discover the finest local products and exclusive deals straight from Mindanao.</p>
                </div>

                <div class="space-y-6 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold uppercase tracking-widest">Exclusive Early Access</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold uppercase tracking-widest">Authentic Local Products</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold uppercase tracking-widest">Order Tracking</span>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="md:w-1/2 p-12 lg:p-16 bg-white">
                <h1 class="text-3xl font-black text-gray-900 mb-2">Create Account</h1>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px] mb-8">Join our thriving community</p>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="text-sm text-red-800 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <x-honeypot />

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all @error('name') border-red-500 @enderror" 
                            placeholder="Juan Dela Cruz" 
                            required 
                            autofocus
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all @error('email') border-red-500 @enderror" 
                            placeholder="juan@example.ph" 
                            required
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                            Create Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all @error('password') border-red-500 @enderror" 
                            placeholder="••••••••" 
                            required
                        >
                        <p class="text-xs text-gray-400 mt-1">At least 8 characters</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all" 
                            placeholder="••••••••" 
                            required
                        >
                    </div>

                    {{-- reCAPTCHA v3 token (populated by JS on submit) --}}
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-register">

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            id="register-submit-btn"
                            type="submit" 
                            class="w-full py-4 bg-brand-600 hover:bg-brand-700 text-white font-black uppercase tracking-widest rounded-lg shadow-lg shadow-brand-100 transition-all transform hover:scale-[1.02]"
                        >
                            Start Exploring
                        </button>
                    </div>

                    @if(config('services.recaptcha.site_key'))
                    <p class="text-[10px] text-gray-400 text-center">
                        Protected by reCAPTCHA &mdash;
                        <a href="https://policies.google.com/privacy" class="underline hover:text-gray-600" target="_blank" rel="noopener noreferrer">Privacy</a> &amp;
                        <a href="https://policies.google.com/terms" class="underline hover:text-gray-600" target="_blank" rel="noopener noreferrer">Terms</a> apply.
                    </p>
                    @endif

                    <!-- Login Link -->
                    <div class="pt-6 text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            Already a member? 
                            <a href="{{ route('login') }}" class="text-brand-600 hover:underline">Log in</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(config('services.recaptcha.site_key'))
<script>
(function () {
    // Lazy-load reCAPTCHA only on this page — does NOT block page rendering
    var s = document.createElement('script');
    s.src = 'https://www.google.com/recaptcha/api.js?render={{ config("services.recaptcha.site_key") }}';
    s.async = true;
    s.defer = true;
    document.head.appendChild(s);

    var form = document.querySelector('form[action*="register"]');
    var btn  = document.getElementById('register-submit-btn');
    if (!form || !btn) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        btn.disabled = true;
        btn.textContent = 'Verifying…';

        function execute() {
            if (typeof grecaptcha === 'undefined') {
                return setTimeout(execute, 80);
            }
            grecaptcha.ready(function () {
                grecaptcha.execute('{{ config("services.recaptcha.site_key") }}', { action: 'register' })
                    .then(function (token) {
                        document.getElementById('g-recaptcha-register').value = token;
                        form.submit();
                    })
                    .catch(function () {
                        btn.disabled = false;
                        btn.textContent = 'Start Exploring';
                    });
            });
        }
        execute();
    });
}());
</script>
@else
<script>
/* reCAPTCHA not configured — form submits directly */
</script>
@endif
@endpush
