@extends('layouts.app')

@section('meta_title', 'Login - DealMindanao')
@section('meta_description', 'Sign in to your DealMindanao account to access exclusive local deals.')

@section('content')
<div class="min-h-screen bg-gray-50 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-white via-gray-50 to-gray-100 py-32">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-2xl shadow-brand-100 overflow-hidden flex flex-col md:flex-row border border-gray-100">
            <!-- Side Decor -->
            <div class="md:w-1/2 bg-brand-600 p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-black italic tracking-tighter">DEALMINDANAO</h2>
                    <p class="mt-6 text-brand-100 font-medium leading-relaxed">Access the best local products and exclusive regional deals.</p>
                </div>
                
                <div class="space-y-6 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold uppercase tracking-widest">Global Shipping</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold uppercase tracking-widest">Local Artisans</span>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="md:w-1/2 p-12 lg:p-16 bg-white">
                <h1 class="text-3xl font-black text-gray-900 mb-2">Welcome Back</h1>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px] mb-8">Sign in to your account</p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

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

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent @error('email') border-red-500 @enderror" 
                            placeholder="you@example.com" 
                            required 
                            autofocus
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-brand-600 uppercase tracking-widest hover:underline">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent @error('password') border-red-500 @enderror" 
                            placeholder="••••••••" 
                            required
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember_me" 
                            name="remember" 
                            class="w-4 h-4 text-brand-600 rounded border-gray-300 focus:ring-brand-500"
                        >
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-5 bg-brand-600 hover:bg-brand-700 text-white font-black uppercase tracking-widest rounded-lg shadow-lg shadow-brand-100 transition-all transform hover:scale-[1.02]"
                    >
                        Sign In
                    </button>

                    <!-- Register Link -->
                    <div class="pt-8 text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            New here? 
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-brand-600 hover:underline">Create an account</a>
                            @endif
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
