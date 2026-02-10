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
        @if(app()->environment('production'))
            <meta name="robots" content="noindex, nofollow">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
            <div class="mb-6 text-center">
                <a href="/" class="inline-flex items-center gap-3">
                    <x-application-logo class="w-16 h-16 fill-current text-gray-500" />
                    <span class="text-lg font-semibold text-gray-900">DealMindanao</span>
                </a>
                <p class="mt-2 text-xs text-gray-500">Local deals. Order online, pay offline.</p>
            </div>

            <div class="w-full max-w-md card">
                <div class="space-y-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
