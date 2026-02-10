<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'DealMindanao Admin' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell">

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-sidebar-inner border-b">
            <div class="brand">DealMindanao</div>
            <p class="text-xs text-gray-500 mt-1">Admin Panel</p>
        </div>

        <nav class="admin-nav px-4 py-5">
            <a href="/admin" class="side-link {{ request()->is('admin') ? 'is-active' : '' }}">Dashboard</a>
            <a href="/admin/products" class="side-link {{ request()->is('admin/products*') ? 'is-active' : '' }}">Products</a>
            <a href="/admin/orders" class="side-link {{ request()->is('admin/orders*') ? 'is-active' : '' }}">Orders</a>
            <a href="/admin/companies" class="side-link {{ request()->is('admin/companies*') ? 'is-active' : '' }}">Companies</a>
            <a href="/admin/categories" class="side-link {{ request()->is('admin/categories*') ? 'is-active' : '' }}">Categories</a>
            <a href="/admin/pages" class="side-link {{ request()->is('admin/pages*') ? 'is-active' : '' }}">Pages</a>
            <a href="/admin/navigation" class="side-link {{ request()->is('admin/navigation*') ? 'is-active' : '' }}">Navigation</a>

            <div class="border-t my-3"></div>

            <a href="/" class="side-link text-gray-400">← Back to site</a>
        </nav>
    </aside>

    <main class="admin-main">
        <div class="admin-content">
            <div class="page-section">
                @yield('content')
            </div>
        </div>
    </main>
</div>

</body>
</html>
