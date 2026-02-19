<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @unless(app()->environment(['local', 'development']))
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com;">
    @endunless
    
    <title>@yield('title', 'Admin') - DealMindanao</title>
    <link rel="icon" type="image/png" href="/favicon.png?v={{ file_exists(public_path('favicon.png')) ? filemtime(public_path('favicon.png')) : '0' }}">
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="admin-layout">
    {{-- Admin Sidebar --}}
    @include('partials.admin-sidebar')
    
    {{-- Main Content --}}
    <main class="admin-main">
        @yield('content')
    </main>
    
    {{-- Modals (outside main content for proper positioning) --}}
    @stack('modals')
    
    {{-- Toast Notifications --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    @stack('scripts')
</body>
</html>
