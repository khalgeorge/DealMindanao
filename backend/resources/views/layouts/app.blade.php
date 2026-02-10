<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
@php
    $defaultTitle = config('app.name', 'DealMindanao') . ' - Local Deals in Mindanao';
    $metaTitle = trim($__env->yieldContent('meta_title', $defaultTitle));
    $metaDescription = trim($__env->yieldContent('meta_description', 'Discover local deals from trusted Mindanao partners. Order online, pay offline.'));
    $metaImage = trim($__env->yieldContent('meta_image', ''));
    $metaUrl = request()->fullUrl();
@endphp

        <title>{{ $metaTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">

        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ $metaUrl }}">
        @if($metaImage)
            <meta property="og:image" content="{{ $metaImage }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans">
        <div class="min-h-screen">
            <header class="sticky top-0 z-40">
                @include('layouts.navigation')
            </header>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="page-shell py-6">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="page-shell py-8 lg:py-10">
                <div class="page-section">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot ?? '' }}
                    @endif
                </div>
            </main>
        </div>
    </body>
</html>
