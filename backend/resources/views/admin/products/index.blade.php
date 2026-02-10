@extends('layouts.admin')

@section('content')
<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h1>Products</h1>
        <p>Manage listings, prices, and availability.</p>
    </div>
    <button type="button" class="btn-primary" onclick="openAddProductModal()">+ Add Product</button>
</div>

<form method="GET" action="/admin/products" class="card lg:sticky lg:top-6 z-20">
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-6">
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

    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div>
            <label>Min Price</label>
            <input name="min_price" value="{{ request('min_price') }}" class="input" placeholder="0.00" inputmode="decimal">
        </div>
        <div>
            <label>Max Price</label>
            <input name="max_price" value="{{ request('max_price') }}" class="input" placeholder="0.00" inputmode="decimal">
        </div>
        <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
            <input type="checkbox" name="discount_only" value="1" class="rounded border-gray-300 text-brand shadow-sm focus:ring-brand/30" @checked(request('discount_only'))>
            Discount only
        </label>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        <button class="btn-primary" type="submit">Apply Filters</button>
        <button class="btn-secondary" type="button" id="adminClearFilters">Clear</button>
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

<div id="adminActiveFilters" class="mt-4 flex flex-wrap items-center gap-2 {{ $hasFilters ? '' : 'hidden' }}">
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

<div id="addProductModal" class="modal-overlay">
    <div class="modal-panel">
        <div class="card p-0 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-lg font-semibold">Add Product</h2>
                <button type="button" class="text-gray-400 hover:text-gray-700" onclick="closeAddProductModal()">&times;</button>
            </div>

            <form action="/admin/products" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm">Product Name</label>
                    <input name="name" class="input" required>
                    @if(!app()->environment('production'))
                        @error('name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="text-sm">Meta Title</label>
                    <input name="meta_title" class="input">
                    <p class="text-xs text-gray-500 mt-1">Optional. Falls back to the product name.</p>
                    @if(!app()->environment('production'))
                        @error('meta_title')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="text-sm">Price</label>
                    <input name="price" type="number" step="0.01" class="input" required>
                    @if(!app()->environment('production'))
                        @error('price')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="text-sm">Discount</label>
                    <input name="discount" type="number" step="0.01" class="input">
                    @if(!app()->environment('production'))
                        @error('discount')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="text-sm">Company</label>
                    <select name="company_id" class="select" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @if(!app()->environment('production'))
                        @error('company_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div>
                    <label class="text-sm">Category</label>
                    <select name="category_id" class="select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @if(!app()->environment('production'))
                        @error('category_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm">Description</label>
                <textarea name="description" class="textarea" rows="4"></textarea>
                @if(!app()->environment('production'))
                    @error('description')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="mt-4">
                <label class="text-sm">Meta Description</label>
                <textarea name="meta_description" class="textarea" rows="3"></textarea>
                <p class="text-xs text-gray-500 mt-1">Optional. Used for search and sharing.</p>
                @if(!app()->environment('production'))
                    @error('meta_description')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="mt-4">
                <label class="text-sm">Images</label>
                <input type="file" name="images[]" multiple class="input">
                @if(!app()->environment('production'))
                    @error('images')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="mt-4 flex gap-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_featured" value="1">
                    Featured
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" checked>
                    Active
                </label>
            </div>

                <div class="mt-6 flex gap-3">
                    <button class="btn-primary" type="submit">Save Product</button>
                    <button class="btn-secondary" type="button" onclick="closeAddProductModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="space-y-6">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div id="adminProducts" data-csrf="{{ csrf_token() }}">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <span class="inline-flex items-center gap-1">
                                Name
                                <span class="text-xs text-gray-400">↕</span>
                            </span>
                        </th>
                        <th>
                            <span class="inline-flex items-center gap-1">
                                Price
                                <span class="text-xs text-gray-400">↕</span>
                            </span>
                        </th>
                        <th>
                            <span class="inline-flex items-center gap-1">
                                Company
                                <span class="text-xs text-gray-400">↕</span>
                            </span>
                        </th>
                        <th>Status</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody id="adminProductsBody">
                    @forelse($products as $product)
                        <tr>
                            <td class="font-medium py-4">{{ $product->name }}</td>
                            <td class="py-4 text-brand font-semibold">₱{{ number_format($product->price, 2) }}</td>
                            <td class="py-4">{{ $product->company->name ?? '-' }}</td>
                            <td class="py-4">
                                @if($product->is_active)
                                    <span class="badge-success">Active</span>
                                @else
                                    <span class="badge-gray">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right pr-4 py-4 space-x-3">
                                <a href="/admin/products/{{ $product->id }}/edit" class="btn-secondary">Edit</a>
                                <form action="/admin/products/{{ $product->id }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">
                                <div class="space-y-2">
                                    <p>No products found.</p>
                                    @if($hasFilters)
                                        <a href="/admin/products" class="btn-secondary">Clear filters</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div data-server-pagination>
            {{ $products->links() }}
        </div>
    </div>

    <div id="adminProductsPagination"></div>
</div>

<div id="adminProductsOverlay" class="page-overlay">
    <div class="card flex items-center gap-3">
        <div class="h-6 w-6 rounded-full border-2 border-brand border-t-transparent animate-spin"></div>
        <p class="text-sm font-semibold text-gray-700">Loading products...</p>
    </div>
</div>

<div id="adminProductsSkeleton" class="hidden mt-6 fade-in">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th class="text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 4; $i++)
                    <tr>
                        <td class="py-4">
                            <div class="h-4 w-32 rounded bg-gray-100 animate-pulse"></div>
                        </td>
                        <td class="py-4">
                            <div class="h-4 w-16 rounded bg-gray-100 animate-pulse"></div>
                        </td>
                        <td class="py-4">
                            <div class="h-4 w-24 rounded bg-gray-100 animate-pulse"></div>
                        </td>
                        <td class="py-4">
                            <div class="h-4 w-14 rounded bg-gray-100 animate-pulse"></div>
                        </td>
                        <td class="py-4 text-right pr-4">
                            <div class="h-8 w-24 rounded bg-gray-100 animate-pulse ml-auto"></div>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<script>
    function openAddProductModal() {
        const modal = document.getElementById('addProductModal');
        if (modal) {
            modal.classList.add('is-open');
        }
    }

    function closeAddProductModal() {
        const modal = document.getElementById('addProductModal');
        if (modal) {
            modal.classList.remove('is-open');
        }
    }

    (() => {
        const form = document.querySelector('form[action="/admin/products"]');
        const container = document.getElementById('adminProducts');
        const body = document.getElementById('adminProductsBody');
        const pagination = document.getElementById('adminProductsPagination');
        const overlay = document.getElementById('adminProductsOverlay');
        const activeFilters = document.getElementById('adminActiveFilters');
        if (!form || !container || !body || !pagination || !overlay) return;

        const csrfToken = container.dataset.csrf;
        const searchInput = form.querySelector('input[name="q"]');
        const selects = form.querySelectorAll('select');
        const clearButton = document.getElementById('adminClearFilters');
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

        const toggleLoading = (isLoading) => {
            const skeleton = document.getElementById('adminProductsSkeleton');
            if (!skeleton) return;
            if (isLoading) {
                container.classList.add('hidden');
                pagination.classList.add('hidden');
                skeleton.classList.remove('hidden');
                skeleton.classList.add('is-visible');
                overlay.classList.add('is-active');
            } else {
                container.classList.remove('hidden');
                pagination.classList.remove('hidden');
                skeleton.classList.add('hidden');
                skeleton.classList.remove('is-visible');
                overlay.classList.remove('is-active');
            }
        };

        const renderRows = (items) => {
            if (!items.length) {
                const clearButton = ${$hasFilters ? 'true' : 'false'}
                    ? '<div class="mt-2"><a href="/admin/products" class="btn-secondary">Clear filters</a></div>'
                    : '';
                body.innerHTML = `
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">
                            <div class="space-y-1">
                                <p>No products found.</p>
                                ${clearButton}
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            body.innerHTML = items.map((item) => {
                const status = item.is_active
                    ? '<span class="badge-success">Active</span>'
                    : '<span class="badge-gray">Inactive</span>';

                return `
                    <tr>
                        <td class="font-medium py-4">${item.name}</td>
                        <td class="py-4 text-brand font-semibold">₱${formatCurrency(item.price)}</td>
                        <td class="py-4">${item.company ?? '-'}</td>
                        <td class="py-4">${status}</td>
                        <td class="text-right pr-4 py-4 space-x-3">
                            <a href="/admin/products/${item.id}/edit" class="btn-secondary">Edit</a>
                            <form action="/admin/products/${item.id}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                `;
            }).join('');
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

        const fetchProducts = async (page = 1) => {
            const params = new URLSearchParams(new FormData(form));
            params.set('page', page);

            toggleLoading(true);
            const response = await fetch(`/admin/products/search?${params.toString()}`);
            if (!response.ok) {
                toggleLoading(false);
                return;
            }

            const payload = await response.json();
            renderRows(payload.data || []);
            renderPagination(payload.meta || {});
            renderChips();
            toggleLoading(false);
        };

        const serverPagination = container.querySelector('[data-server-pagination]');
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