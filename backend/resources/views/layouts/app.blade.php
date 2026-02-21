<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $defaultTitle = config('app.name', 'DealMindanao') . ' - Authentic Products from Mindanao';
        $metaTitle = trim($__env->yieldContent('meta_title', $defaultTitle));
        $metaDescription = trim($__env->yieldContent('meta_description', 'Discover authentic products from Mindanao - handcrafted items, local delicacies, and unique treasures from Filipino artisans.'));
        $metaKeywords = trim($__env->yieldContent('meta_keywords', 'Mindanao products, Philippine handicrafts, local delicacies, artisan products'));
        $metaImage = trim($__env->yieldContent('meta_image', asset('logo_main-final.png')));
        $metaCanonical = trim($__env->yieldContent('canonical', '')) ?: request()->url();
    @endphp
    
    {{-- SEO Meta Tags --}}
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <link rel="canonical" href="{{ $metaCanonical }}">
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:url" content="{{ $metaCanonical }}">
    <meta property="og:type" content="website">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="/favicon.png?v={{ file_exists(public_path('favicon.png')) ? filemtime(public_path('favicon.png')) : '0' }}">

    {{-- Vite Assets --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    {{-- Navbar --}}
    @include('partials.navbar')
    
    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>
    
    {{-- Footer --}}
    @include('partials.footer')
    
    {{-- Toast Notifications Container --}}
    <div id="toast-container" class="fixed top-24 right-4 z-50 space-y-2"></div>
    
    @stack('scripts')
</body>
</html>
