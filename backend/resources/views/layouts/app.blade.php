<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        // Laravel 12: @section('name', $value) calls e() internally, so we
        // must html_entity_decode before re-escaping with {{ }} to avoid
        // double-encoding (e.g. "Trust &amp;amp; Safety").
        $hed = fn(string $v) => html_entity_decode($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $defaultTitle = config('app.name', 'DealMindanao') . ' - Authentic Products from Mindanao';
        $metaTitle       = $hed(trim($__env->yieldContent('meta_title', $defaultTitle)));
        $metaDescription = $hed(trim($__env->yieldContent('meta_description', 'Discover authentic products from Mindanao - handcrafted items, local delicacies, and unique treasures from Filipino artisans.')));
        $metaKeywords    = $hed(trim($__env->yieldContent('meta_keywords', 'Mindanao products, Philippine handicrafts, local delicacies, artisan products')));
        $metaImage       = $hed(trim($__env->yieldContent('meta_image', '')) ?: 'https://dealmindanao.com/logo_main-final.png');
        $metaCanonical   = $hed(trim($__env->yieldContent('canonical', '')) ?: request()->url());
        $metaRobots      = $hed(trim($__env->yieldContent('meta_robots', '')));
    @endphp
    
    {{-- SEO Meta Tags --}}
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <link rel="canonical" href="{{ $metaCanonical }}">
    @if($metaRobots)
    <meta name="robots" content="{{ $metaRobots }}">
    @endif
    
    {{-- Open Graph --}}
    <meta property="og:site_name" content="DealMindanao">
    <meta property="og:locale" content="en_PH">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:url" content="{{ url()->current() }}">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="/favicon.png?v={{ file_exists(public_path('favicon.png')) ? filemtime(public_path('favicon.png')) : '0' }}">

    {{-- Structured Data: Organization --}}
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "DealMindanao",
        "url": "https://dealmindanao.com",
        "logo": "https://dealmindanao.com/logo_main-final.png",
        "sameAs": [
            "https://www.facebook.com/dealmindanao"
        ]
    }
    </script>
    @endverbatim

    {{-- Structured Data: WebSite with SearchAction --}}
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "DealMindanao",
        "url": "https://dealmindanao.com",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "https://dealmindanao.com/shop?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    @endverbatim

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
