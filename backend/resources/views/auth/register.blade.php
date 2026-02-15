@extends('layouts.app')

@section('meta_title', 'Join DealMindanao')
@section('meta_description', 'Create an account to access exclusive local deals from Mindanao.')

@section('content')
<div class="min-h-screen bg-gray-50 py-32">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-2xl shadow-brand-100 overflow-hidden flex flex-col md:flex-row-reverse border border-gray-100">
            <!-- Side Decor -->
            <div class="md:w-1/2 bg-gray-900 p-12 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-brand-600 rounded-full blur-2xl opacity-50"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-black italic tracking-tighter">DEALMINDANAO</h2>
                    <p class="mt-6 text-gray-400 font-medium leading-relaxed italic">"Bringing Mindanao's quality to the center stage."</p>
                </div>
                
                <div class="bg-white/5 p-6 rounded-lg backdrop-blur-sm border border-white/10 relative z-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-brand-400 mb-2">Benefit #1</p>
                    <h4 class="font-black text-lg">Exclusive Early Access</h4>
                    <p class="text-sm text-gray-400 mt-2">Be the first to know about limited handicrafts and seasonal harvests.</p>
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

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                            Full Name
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent @error('name') border-red-500 @enderror" 
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
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent @error('email') border-red-500 @enderror" 
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
                            Create Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent @error('password') border-red-500 @enderror" 
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
                            Confirm Password
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent" 
                            placeholder="••••••••" 
                            required
                        >
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            type="submit" 
                            class="w-full py-4 bg-brand-600 hover:bg-brand-700 text-white font-black uppercase tracking-widest rounded-lg shadow-lg shadow-brand-100 transition-all transform hover:scale-[1.02]"
                        >
                            Start Exploring
                        </button>
                    </div>

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
