@extends('layouts.app')

@section('meta_title', 'Shop Deals | DealMindanao')
@section('meta_description', 'Browse curated local deals from trusted partners across Mindanao.')

@section('content')
<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
    <div>
        <h1>Shop Deals</h1>
        <p>Find the latest local offers from Mindanao partners.</p>
    </div>
</div>

<form method="GET" action="/shop" class="card">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <label>Search</label>
            <input name="q" value="{{ $search }}" class="input" placeholder="Search products">
        </div>
        <div>
            <label>Company</label>
            <select name="company" class="select">
                <option value="">All companies</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" @selected((string) $companyId === (string) $company->id)>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Category</label>
            <select name="category" class="select">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Sort By</label>
            <select name="sort" class="select">
                <option value="latest" @selected($sort === 'latest')>Latest</option>
                <option value="name" @selected($sort === 'name')>Name (A-Z)</option>
                <option value="price_asc" @selected($sort === 'price_asc')>Price (Low to High)</option>
                <option value="price_desc" @selected($sort === 'price_desc')>Price (High to Low)</option>
            </select>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <label>Min Price</label>
            <input name="min_price" value="{{ request('min_price') }}" class="input" placeholder="0.00" inputmode="decimal">
        </div>
        <div>
            <label>Max Price</label>
            <input name="max_price" value="{{ request('max_price') }}" class="input" placeholder="0.00" inputmode="decimal">
        </div>
        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 sm:col-span-2 lg:col-span-2">
            <input type="checkbox" name="discount_only" value="1" class="rounded border-gray-300 text-brand shadow-sm focus:ring-brand/30" @checked(request('discount_only'))>
            Discount only
        </label>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        <button class="btn-primary" type="submit">Apply Filters</button>
        <button class="btn-secondary" type="button" id="shopClearFilters">Clear</button>
    </div>
</form>

@php
    $companyName = $companyId ? optional($companies->firstWhere('id', (int) $companyId))->name : null;
    $categoryName = $categoryId ? optional($categories->firstWhere('id', (int) $categoryId))->name : null;
    $sortLabels = [
        'latest' => 'Latest',
        'name' => 'Name (A-Z)',
        'price_asc' => 'Price (Low to High)',
        'price_desc' => 'Price (High to Low)',
    ];
    $minPrice = request('min_price');
    $maxPrice = request('max_price');
    $discountOnly = request('discount_only');
    $minLabel = $minPrice !== null && $minPrice !== '' ? '₱' . number_format((float) $minPrice, 2) : null;
    $maxLabel = $maxPrice !== null && $maxPrice !== '' ? '₱' . number_format((float) $maxPrice, 2) : null;
    $priceLabel = null;
    if ($minLabel && $maxLabel) {
        $priceLabel = $minLabel . ' - ' . $maxLabel;
    } elseif ($minLabel) {
        $priceLabel = $minLabel . '+';
    } elseif ($maxLabel) {
        $priceLabel = '≤ ' . $maxLabel;
    }
    $hasFilters = $search || $companyId || $categoryId || ($sort && $sort !== 'latest') || $priceLabel || $discountOnly;
@endphp

<div id="shopActiveFilters" class="mt-4 flex flex-wrap items-center gap-2 {{ $hasFilters ? '' : 'hidden' }}">
    @if($search)
        <button type="button" class="pill" data-filter="q">Search: {{ $search }} ×</button>
    @endif
    @if($companyId)
        <button type="button" class="pill" data-filter="company">Company: {{ $companyName ?? 'Selected' }} ×</button>
    @endif
    @if($categoryId)
        <button type="button" class="pill" data-filter="category">Category: {{ $categoryName ?? 'Selected' }} ×</button>
    @endif
    @if($sort && $sort !== 'latest')
        <button type="button" class="pill" data-filter="sort">Sort: {{ $sortLabels[$sort] ?? 'Custom' }} ×</button>
    @endif
    @if($priceLabel)
        <button type="button" class="pill" data-filter="price">Price: {{ $priceLabel }} ×</button>
    @endif
    @if($discountOnly)
        <button type="button" class="pill" data-filter="discount">Discount only ×</button>
    @endif
    @if($hasFilters)
        <button type="button" class="btn-secondary" data-filter="all">Clear all</button>
    @endif
</div>

<div id="shopResults" data-csrf="{{ csrf_token() }}">
    @if($products->count())
        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($products as $product)
                @php
                    $imageUrl = $product->images && count($product->images)
                        ? \Illuminate\Support\Facades\Storage::url($product->images[0])
                        : null;
                @endphp
                <div class="card card-hover relative flex h-full flex-col">
                    <a href="/shop/{{ $product->slug }}" class="absolute inset-0" aria-label="View {{ $product->name }}"></a>
                    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-xs text-gray-400">No image</div>
                        @endif
                    </div>

                    <div class="mt-4 flex flex-1 flex-col">
                        <div class="flex-1">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h2>
                            <p class="text-sm text-gray-500">{{ $product->company->name }}</p>
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <p class="text-lg font-bold text-brand">
                                ₱{{ number_format($product->price, 2) }}
                            </p>
                            @if($product->discount)
                                <span class="badge-warning">Promo</span>
                            @endif
                        </div>

                        <div class="relative z-10 mt-4 flex flex-wrap gap-2">
                            <a href="/shop/{{ $product->slug }}" class="btn-secondary">View Deal</a>
                            <form action="/cart/add/{{ $product->id }}" method="POST">
                                @csrf
                                <button class="btn-primary" type="submit">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6" data-server-pagination>
            {{ $products->links() }}
        </div>
    @else
        <div class="card text-center p-10">
            <div class="text-5xl mb-3">🔍</div>
            <h2 class="text-xl font-semibold mb-2 text-gray-900">No products found</h2>
            <p>Try adjusting your search or filters.</p>
            @if($hasFilters)
                <div class="mt-4">
                    <a href="/shop" class="btn-secondary">Clear filters</a>
                </div>
            @endif
        </div>
    @endif
</div>

<div id="shopPagination" class="mt-6"></div>

<div id="shopOverlay" class="page-overlay">
    <div class="card flex items-center gap-3">
        <div class="h-6 w-6 rounded-full border-2 border-brand border-t-transparent animate-spin"></div>
        <p class="text-sm font-semibold text-gray-700">Loading deals...</p>
    </div>
</div>

<div id="shopSkeleton" class="hidden mt-6 fade-in">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @for ($i = 0; $i < 3; $i++)
            <div class="card animate-pulse">
                <div class="aspect-[4/3] rounded-xl bg-gray-100 mb-4"></div>
                <div class="h-4 rounded bg-gray-100 w-3/4 mb-2"></div>
                <div class="h-3 rounded bg-gray-100 w-1/2"></div>
            </div>
        @endfor
    </div>
</div>

<script>
    (() => {
        const form = document.querySelector('form[action="/shop"]');
        const results = document.getElementById('shopResults');
        const pagination = document.getElementById('shopPagination');
        const overlay = document.getElementById('shopOverlay');
        const activeFilters = document.getElementById('shopActiveFilters');
        if (!form || !results || !pagination || !overlay) return;

        const csrfToken = results.dataset.csrf;
        const searchInput = form.querySelector('input[name="q"]');
        const selects = form.querySelectorAll('select');
        const clearButton = document.getElementById('shopClearFilters');
        let timer;

        const formatCurrency = (value) => {
            return new Intl.NumberFormat('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
        };

        const renderChips = () => {
            if (!activeFilters) return;
            const searchValue = searchInput?.value?.trim() ?? '';
            const companySelect = form.querySelector('select[name="company"]');
            const categorySelect = form.querySelector('select[name="category"]');
            const sortSelect = form.querySelector('select[name="sort"]');
            const minPriceInput = form.querySelector('input[name="min_price"]');
            const maxPriceInput = form.querySelector('input[name="max_price"]');
            const discountOnlyInput = form.querySelector('input[name="discount_only"]');
            const minPrice = minPriceInput?.value?.trim() ?? '';
            const maxPrice = maxPriceInput?.value?.trim() ?? '';
            const discountOnly = !!discountOnlyInput?.checked;

            const chips = [];
            if (searchValue) {
                chips.push(`<button type="button" class="pill" data-filter="q">Search: ${searchValue} ×</button>`);
            }
            if (companySelect && companySelect.value) {
                chips.push(`<button type="button" class="pill" data-filter="company">Company: ${companySelect.selectedOptions[0].text} ×</button>`);
            }
            if (categorySelect && categorySelect.value) {
                chips.push(`<button type="button" class="pill" data-filter="category">Category: ${categorySelect.selectedOptions[0].text} ×</button>`);
            }
            if (sortSelect && sortSelect.value && sortSelect.value !== 'latest') {
                chips.push(`<button type="button" class="pill" data-filter="sort">Sort: ${sortSelect.selectedOptions[0].text} ×</button>`);
            }
            if (minPrice || maxPrice) {
                const minValue = Number(minPrice);
                const maxValue = Number(maxPrice);
                const minLabel = Number.isFinite(minValue) && minPrice ? `₱${formatCurrency(minValue)}` : minPrice;
                const maxLabel = Number.isFinite(maxValue) && maxPrice ? `₱${formatCurrency(maxValue)}` : maxPrice;
                let priceLabel = '';
                if (minLabel && maxLabel) {
                    priceLabel = `${minLabel} - ${maxLabel}`;
                } else if (minLabel) {
                    priceLabel = `${minLabel}+`;
                } else {
                    priceLabel = `≤ ${maxLabel}`;
                }
                chips.push(`<button type="button" class="pill" data-filter="price">Price: ${priceLabel} ×</button>`);
            }
            if (discountOnly) {
                chips.push('<button type="button" class="pill" data-filter="discount">Discount only ×</button>');
            }
            if (chips.length) {
                chips.push('<button type="button" class="btn-secondary" data-filter="all">Clear all</button>');
            }

            activeFilters.innerHTML = chips.join('');
            activeFilters.classList.toggle('hidden', chips.length === 0);
        };

        const renderProducts = (items) => {
            if (!items.length) {
                const clearButton = ${$hasFilters ? 'true' : 'false'}
                    ? '<div class="mt-4"><a href="/shop" class="btn-secondary">Clear filters</a></div>'
                    : '';
                results.innerHTML = `
                    <div class="card text-center p-10">
                        <div class="text-5xl mb-3">🔍</div>
                        <h2 class="text-xl font-semibold mb-2">No products found</h2>
                        <p class="text-gray-500">Try adjusting your search or filters.</p>
                        ${clearButton}
                    </div>
                `;
                return;
            }

            const cards = items.map((item) => {
                const image = item.image_url
                    ? `<img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-cover">`
                    : '';

                return `
                    <div class="card card-hover relative flex h-full flex-col">
                        <a href="/shop/${item.slug}" class="absolute inset-0" aria-label="View ${item.name}"></a>
                        <div class="aspect-[4/3] bg-gray-100 rounded-xl overflow-hidden">${image}</div>
                        <div class="mt-4 flex flex-1 flex-col">
                            <div class="flex-1">
                                <h2 class="text-lg font-semibold text-gray-900">${item.name}</h2>
                                <p class="text-sm text-gray-500">${item.company ?? ''}</p>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-lg font-bold text-brand">₱${formatCurrency(item.price)}</p>
                                ${item.discount ? '<span class="badge-warning">Promo</span>' : ''}
                            </div>
                            <div class="relative z-10 mt-4 flex flex-wrap gap-2">
                                <a href="/shop/${item.slug}" class="btn-secondary">View Deal</a>
                                <form action="/cart/add/${item.id}" method="POST">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <button class="btn-primary" type="submit">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            results.innerHTML = `<div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">${cards}</div>`;
        };

        const renderPagination = (meta) => {
            if (!meta || meta.last_page <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let buttons = '';
            for (let page = 1; page <= meta.last_page; page += 1) {
                const isActive = page === meta.current_page;
                buttons += `
                    <button type="button" data-page="${page}" class="btn-secondary ${isActive ? 'opacity-60 cursor-default' : ''}" ${isActive ? 'disabled' : ''}>
                        ${page}
                    </button>
                `;
            }

            pagination.innerHTML = `<div class="flex flex-wrap gap-2">${buttons}</div>`;
        };

        const toggleLoading = (isLoading) => {
            const skeleton = document.getElementById('shopSkeleton');
            if (!skeleton) return;
            if (isLoading) {
                results.classList.add('hidden');
                pagination.classList.add('hidden');
                skeleton.classList.remove('hidden');
                skeleton.classList.add('is-visible');
                overlay.classList.add('is-active');
            } else {
                results.classList.remove('hidden');
                pagination.classList.remove('hidden');
                skeleton.classList.add('hidden');
                skeleton.classList.remove('is-visible');
                overlay.classList.remove('is-active');
            }
        };

        const fetchProducts = async (page = 1) => {
            const params = new URLSearchParams(new FormData(form));
            params.set('page', page);

            toggleLoading(true);
            const response = await fetch(`/shop/search?${params.toString()}`);
            if (!response.ok) {
                toggleLoading(false);
                return;
            }

            const payload = await response.json();
            renderProducts(payload.data || []);
            renderPagination(payload.meta || {});
            renderChips();
            toggleLoading(false);
        };

        const serverPagination = results.querySelector('[data-server-pagination]');
        if (serverPagination) {
            serverPagination.classList.add('hidden');
        }

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(() => fetchProducts(1), 500);
            });
        }

        selects.forEach((select) => {
            select.addEventListener('change', () => fetchProducts(1));
        });

        if (clearButton) {
            clearButton.addEventListener('click', () => {
                form.reset();
                history.replaceState(null, '', window.location.pathname);
                renderChips();
                fetchProducts(1);
            });
        }

        if (activeFilters) {
            activeFilters.addEventListener('click', (event) => {
                const target = event.target;
                if (!(target instanceof HTMLElement)) return;
                const filter = target.dataset.filter;
                if (!filter) return;

                if (filter === 'q' && searchInput) {
                    searchInput.value = '';
                } else if (filter === 'company') {
                    const select = form.querySelector('select[name="company"]');
                    if (select) select.value = '';
                } else if (filter === 'category') {
                    const select = form.querySelector('select[name="category"]');
                    if (select) select.value = '';
                } else if (filter === 'sort') {
                    const select = form.querySelector('select[name="sort"]');
                    if (select) select.value = 'latest';
                } else if (filter === 'price') {
                    const minInput = form.querySelector('input[name="min_price"]');
                    const maxInput = form.querySelector('input[name="max_price"]');
                    if (minInput) minInput.value = '';
                    if (maxInput) maxInput.value = '';
                } else if (filter === 'discount') {
                    const discountInput = form.querySelector('input[name="discount_only"]');
                    if (discountInput) discountInput.checked = false;
                } else if (filter === 'all') {
                    form.reset();
                }

                fetchProducts(1);
            });
        }

        pagination.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement)) return;
            const page = target.dataset.page;
            if (page) {
                fetchProducts(Number(page));
            }
        });

        renderChips();
        fetchProducts(Number(new URLSearchParams(window.location.search).get('page') || 1));
    })();
</script>

@endsection
