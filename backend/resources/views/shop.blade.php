@extends('layouts.app')

@section('meta_title', 'Shop Local Deals in Mindanao | Hardware, Food and More – DealMindanao')
@section('meta_description', 'Browse hardware and local products from verified Mindanao sellers. Order online and pay offline via GCash or Bank Transfer after confirmation.')
@section('meta_keywords', 'hardware deals Mindanao, buy online Philippines, local products, GCash Bank Transfer, DealMindanao shop')
@section('canonical', url('/shop'))
@section('og_url',    url('/shop'))
@section('meta_robots', 'index,follow')

@section('content')
<div class="page-shell py-8">

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Shop Local Deals in Mindanao</h1>
        <p class="text-gray-600 mt-2">Browse verified hardware and local products from trusted Mindanao sellers. Order online — our team reviews your request and contacts you to confirm payment and arrange delivery.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-64 space-y-8 flex-shrink-0">

            <!-- Search -->
            <div>
                <label class="block mb-2 font-bold uppercase tracking-tight text-xs text-gray-500">Search</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" id="search-input" class="input pl-10 h-11" placeholder="Search keywords...">
                </div>
            </div>

            <!-- Category -->
            <div>
                <label class="block mb-3 font-bold uppercase tracking-tight text-xs text-gray-500">Category</label>
                <div id="category-filters" class="space-y-2">
                    @foreach($categories as $category)
                    <label class="flex items-center group cursor-pointer">
                        <input type="checkbox" value="{{ $category->id }}" class="w-4 h-4 text-brand-600 rounded border-gray-300 focus:ring-brand-500">
                        <span class="ml-3 text-sm capitalize text-gray-700 group-hover:text-brand-600">{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block mb-2 font-bold uppercase tracking-tight text-xs text-gray-500">Sort By</label>
                <select id="sort-select" class="input h-11">
                    <option value="newest">Newest First</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>

            <div>
                <p class="text-xs text-gray-500 mt-2">
                    Verified Mindanao sellers &bull; Offline payment &bull; Manual order confirmation
                </p>
            </div>

            <!-- Reset Filters -->
            <button id="reset-filters-btn" class="btn-outline w-full">
                <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reset Filters
            </button>

        </aside>

        <!-- Results Area -->
        <div class="flex-1">

            <!-- Grid Header -->
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500"><span id="result-count" class="font-bold text-gray-900">0</span> products found</p>
            </div>

            <!-- Loading Skeleton -->
            <div id="loading-skeleton" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="animate-pulse">
                    <div class="bg-gray-200 aspect-[4/5] rounded-lg mb-4"></div>
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div class="h-6 bg-gray-200 rounded w-1/4"></div>
                </div>
                <div class="animate-pulse">
                    <div class="bg-gray-200 aspect-[4/5] rounded-lg mb-4"></div>
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div class="h-6 bg-gray-200 rounded w-1/4"></div>
                </div>
                <div class="animate-pulse">
                    <div class="bg-gray-200 aspect-[4/5] rounded-lg mb-4"></div>
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div class="h-6 bg-gray-200 rounded w-1/4"></div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="hidden text-center py-20 card border-dashed border-2">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">No deals found</h2>
                <p class="text-gray-500 mb-4">Try adjusting your filters, or check back soon — new Mindanao deals are added regularly.</p>
                <p class="text-gray-500 mb-8">Want to list your products? <a href="/partner" class="text-brand-600 font-semibold hover:underline">Become a Partner &rarr;</a></p>
                <button id="clear-filters" class="btn-primary">Clear Filters</button>
            </div>

            <!-- Products Grid -->
            <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Populated by JS -->
            </div>

            <!-- Pagination Controls -->
            <div id="pagination-controls" class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <label class="text-sm text-gray-600">Show:</label>
                    <select id="rows-per-page" class="input h-10 w-20 focus-visible:ring-0 focus-visible:border-gray-300">
                        <option value="8">8</option>
                        <option value="12">12</option>
                        <option value="16">16</option>
                        <option value="24">24</option>
                        <option value="all">All</option>
                    </select>
                    <span class="text-sm text-gray-600">per page</span>
                </div>
                <div class="flex items-center gap-2">
                    <button id="prev-page" class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <span id="page-info" class="text-sm text-gray-600 px-4">Page 1 of 1</span>
                    <button id="next-page" class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-12 text-center">
                <p class="text-sm text-gray-500">All prices are indicative. Final pricing and availability are confirmed by our team after order review. Order online, pay offline via GCash or Bank Transfer.</p>
            </div>

        </div>
    </div>
</div>

<!-- Product Detail Modal -->
<div id="product-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" id="modal-backdrop"></div>
        <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <button id="close-modal" class="absolute top-4 right-4 z-10 p-2 text-gray-400 hover:text-gray-600 bg-white rounded-full shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div id="modal-content" class="p-6 sm:p-8"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    // ─── Data seeded from Laravel ─────────────────────────────────────────────
    const allProducts = @json($allProducts);

    // ─── State ────────────────────────────────────────────────────────────────
    let filteredProducts = [];
    let currentPage      = 1;
    let rowsPerPage      = 8;

    // ─── DOM refs ─────────────────────────────────────────────────────────────
    const grid         = document.getElementById('products-grid');
    const skeleton     = document.getElementById('loading-skeleton');
    const emptyState   = document.getElementById('empty-state');
    const countLabel   = document.getElementById('result-count');
    const modal        = document.getElementById('product-modal');
    const modalContent = document.getElementById('modal-content');

    // ─── Helpers ──────────────────────────────────────────────────────────────
    function formatPrice(amount) {
        return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg text-white z-[9999] ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // ─── Render products ──────────────────────────────────────────────────────
    function renderProducts(products) {
        filteredProducts = products;
        countLabel.textContent = products.length;

        if (products.length === 0) {
            grid.innerHTML = '';
            emptyState.classList.remove('hidden');
            document.getElementById('pagination-controls').classList.add('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        document.getElementById('pagination-controls').classList.remove('hidden');

        const totalPages   = rowsPerPage === 'all' ? 1 : Math.ceil(products.length / rowsPerPage);
        if (currentPage > totalPages) currentPage = 1;
        const startIndex   = rowsPerPage === 'all' ? 0 : (currentPage - 1) * rowsPerPage;
        const endIndex     = rowsPerPage === 'all' ? products.length : startIndex + rowsPerPage;
        const pageProducts = products.slice(startIndex, endIndex);

        document.getElementById('prev-page').disabled = currentPage === 1;
        document.getElementById('next-page').disabled = currentPage === totalPages;
        document.getElementById('page-info').textContent = `Page ${currentPage} of ${totalPages}`;

        grid.innerHTML = pageProducts.map(p => {
            const salePrice       = p.display_price;
            const discountPercent = p.discount_percent;
            const imageUrl        = p.image || '/images/unknown-product.svg';
            const catLabel        = p.category_name || '';

            return `
                <div class="product-card group flex flex-col">

                    <!-- Image area -->
                    <div class="relative aspect-square cursor-pointer overflow-hidden flex-shrink-0"
                         onclick="window.viewProductModal(${p.id})">
                        <img src="${imageUrl}" alt="${p.name} – Buy local in Mindanao"
                             onerror="this.onerror=null;this.src='/images/unknown-product.svg'"
                             class="w-full h-full object-contain p-4 transition-transform duration-500 group-hover:scale-105">

                        ${p.is_on_promo ? `
                        <div class="absolute top-2.5 right-2.5 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                            ${p.promo_label || discountPercent + '% OFF'}
                        </div>` : ''}

                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-100"></div>

                    <!-- Content -->
                    <div class="p-4 flex flex-col flex-1 gap-1.5">

                        <div class="flex items-center justify-between leading-none">
                            ${catLabel ? `<p class="text-[10px] font-black uppercase tracking-widest text-brand-600">${catLabel}</p>` : '<span></span>'}
                            ${p.region ? `<p class="text-[10px] text-gray-400 font-semibold flex items-center gap-1 uppercase">
                                <svg class="w-2.5 h-2.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                ${p.region}
                            </p>` : ''}
                        </div>

                        <p class="text-sm font-bold uppercase text-gray-900 line-clamp-2 leading-snug">${p.name}</p>

                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-base font-black text-gray-900">${formatPrice(salePrice)}</span>
                            ${p.is_on_promo ? `<span class="text-xs text-gray-400 line-through">${formatPrice(p.price)}</span>` : ''}
                        </div>

                        <div class="flex gap-2 mt-auto pt-3">
                            <button onclick="window.viewProductModal(${p.id})"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </button>
                            <button onclick="window.addToCartFromShop(${p.id})"
                                    class="flex-shrink-0 w-9 h-9 inline-flex items-center justify-center bg-gray-100 hover:bg-brand-50 hover:text-brand-700 text-gray-600 rounded-lg transition-colors border border-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>
            `;
        }).join('');
    }

    // ─── Filtering & Sorting ──────────────────────────────────────────────────
    function applyFilters() {
        const query              = document.getElementById('search-input').value.toLowerCase();
        const selectedCategories = Array.from(document.querySelectorAll('#category-filters input:checked')).map(cb => parseInt(cb.value));
        const sortValue          = document.getElementById('sort-select').value;

        let filtered = allProducts.filter(p => {
            const matchesSearch   = !query ||
                p.name.toLowerCase().includes(query) ||
                (p.company     && p.company.toLowerCase().includes(query)) ||
                (p.description && p.description.toLowerCase().includes(query));
            const matchesCategory = selectedCategories.length === 0 || selectedCategories.includes(p.category_id);
            return matchesSearch && matchesCategory;
        });

        if (sortValue === 'price-low')  filtered.sort((a, b) => a.display_price - b.display_price);
        if (sortValue === 'price-high') filtered.sort((a, b) => b.display_price - a.display_price);
        if (sortValue === 'newest')     filtered.sort((a, b) => b.id - a.id);

        currentPage = 1;
        renderProducts(filtered);
    }

    function resetFilters() {
        document.getElementById('search-input').value = '';
        document.querySelectorAll('#category-filters input').forEach(cb => cb.checked = false);
        document.getElementById('sort-select').value = 'newest';
        currentPage = 1;
        renderProducts(allProducts);
    }

    document.getElementById('search-input').addEventListener('input', applyFilters);
    document.querySelectorAll('#category-filters input').forEach(cb => cb.addEventListener('change', applyFilters));
    document.getElementById('sort-select').addEventListener('change', applyFilters);
    document.getElementById('reset-filters-btn').addEventListener('click', resetFilters);
    document.getElementById('clear-filters').addEventListener('click', resetFilters);

    // ─── Pagination ───────────────────────────────────────────────────────────
    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderProducts(filteredProducts);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    document.getElementById('next-page').addEventListener('click', () => {
        const total = Math.ceil(filteredProducts.length / rowsPerPage);
        if (currentPage < total) {
            currentPage++;
            renderProducts(filteredProducts);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    document.getElementById('rows-per-page').addEventListener('change', e => {
        rowsPerPage = e.target.value === 'all' ? 'all' : parseInt(e.target.value);
        currentPage = 1;
        renderProducts(filteredProducts.length ? filteredProducts : allProducts);
    });

    // ─── Product Modal ────────────────────────────────────────────────────────
    window.viewProductModal = (productId) => {
        const p = allProducts.find(p => p.id === productId);
        if (!p) return;

        // Reset selected variant each time the modal opens
        selectedModalVariant = null;

        const hasVariants    = p.variants?.options?.length > 0;
        const salePrice      = p.display_price;
        const discountPercent = p.discount_percent;
        const imageUrl       = p.image || '/images/unknown-product.svg';

        // Pre-select first variant
        if (hasVariants) {
            selectedModalVariant = p.variants.options[0];
        }

        modalContent.innerHTML = `
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <div class="aspect-square bg-white rounded-lg overflow-hidden">
                        <img src="${imageUrl}" alt="${p.name} – Buy local in Mindanao" class="w-full h-full object-contain" onerror="this.onerror=null;this.src='/images/unknown-product.svg'">
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">${p.name}</h2>
                        ${p.is_on_promo ? `
                        <div class="inline-flex items-center gap-2 bg-brand-50 px-3 py-1 rounded-full mb-4">
                            <span class="text-brand-600 font-bold text-sm">${p.promo_label || discountPercent + '% OFF'}</span>
                        </div>` : ''}
                        <div class="flex items-baseline gap-3 mb-4" id="modal-price-block">
                            <span class="text-4xl font-bold text-gray-900" id="modal-price">${formatPrice(hasVariants ? p.variants.options[0].price : salePrice)}</span>
                            ${p.is_on_promo && !hasVariants ? `<span class="text-xl text-gray-400 line-through">${formatPrice(p.price)}</span>` : ''}
                        </div>
                    </div>
                    ${hasVariants ? `
                    <div class="mb-5">
                        <p class="text-xs font-black uppercase tracking-widest text-gray-500 mb-2">${p.variants.attribute}</p>
                        <div class="flex flex-wrap gap-2" id="modal-variant-options">
                            ${p.variants.options.map((o, i) => `
                                <button type="button"
                                        class="modal-variant-btn px-3 py-1.5 rounded-lg border text-sm font-semibold transition-all
                                               ${i === 0 ? 'border-brand-600 bg-brand-50 text-brand-700' : 'border-gray-200 bg-white text-gray-700 hover:border-gray-400'}"
                                        data-price="${o.price}"
                                        data-stock="${o.stock}"
                                        data-label="${o.label}">
                                    ${o.label}
                                </button>`).join('')}
                        </div>
                    </div>` : ''}
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Product Details</h3>
                        <p class="text-gray-600">${p.description || 'Contact seller for detailed specifications and availability.'}</p>
                    </div>
                    <div class="mt-auto flex gap-3">
                        <button onclick="window.addToCartFromModal(${p.id})" class="btn-primary flex-1">
                            <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Add to Cart
                        </button>
                        <a href="/product/${p.slug}" class="btn-outline flex-1 text-center flex items-center justify-center">
                            Full Details
                        </a>
                    </div>
                </div>
            </div>
        `;

        // Attach variant pill click handlers after innerHTML is set
        if (hasVariants) {
            document.querySelectorAll('.modal-variant-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.modal-variant-btn').forEach(b => {
                        b.classList.remove('border-brand-600', 'bg-brand-50', 'text-brand-700');
                        b.classList.add('border-gray-200', 'bg-white', 'text-gray-700', 'hover:border-gray-400');
                    });
                    btn.classList.add('border-brand-600', 'bg-brand-50', 'text-brand-700');
                    btn.classList.remove('border-gray-200', 'bg-white', 'text-gray-700', 'hover:border-gray-400');

                    selectedModalVariant = {
                        label: btn.dataset.label,
                        price: parseFloat(btn.dataset.price),
                        stock: parseInt(btn.dataset.stock, 10),
                    };
                    document.getElementById('modal-price').textContent = formatPrice(selectedModalVariant.price);
                });
            });
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    document.getElementById('close-modal').addEventListener('click', closeModal);
    document.getElementById('modal-backdrop').addEventListener('click', closeModal);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // ─── Add to Cart (localStorage) ───────────────────────────────────────────

    // Card cart icon: redirect to product page if variants exist
    window.addToCartFromShop = id => {
        const p = allProducts.find(p => p.id == id);
        if (p?.variants?.options?.length) {
            window.location.href = '/product/' + p.slug;
            return;
        }
        addToCart(id, null);
    };

    // Track the currently selected variant inside the quick-view modal
    let selectedModalVariant = null;

    window.addToCartFromModal = id => {
        addToCart(id, selectedModalVariant);
        closeModal();
    };

    function addToCart(productId, variant) {
        const p = allProducts.find(p => p.id == productId);
        if (!p) return;

        // Safety-net: if product has variants but none chosen, redirect to product page
        if (p.variants?.options?.length && !variant) {
            window.location.href = '/product/' + p.slug;
            return;
        }

        // Fall back to product-level simple variant (e.g. "2M") when no option selected
        const effectiveVariant = variant ? variant.label ?? variant : (p.variant || null);

        const price    = variant ? variant.price : p.display_price;
        const cartKey  = effectiveVariant ? `${productId}__${effectiveVariant}` : String(productId);
        const dispName = effectiveVariant ? `${p.name} (${effectiveVariant})` : p.name;

        const cart     = JSON.parse(localStorage.getItem('cart') || '[]');
        const existing = cart.find(item => (item.cart_key ?? String(item.id)) === cartKey);

        if (existing) {
            existing.quantity += 1;
            showToast(`${dispName} quantity updated!`);
        } else {
            cart.push({
                id:                  p.id,
                cart_key:            cartKey,
                name:                dispName,
                variant:             effectiveVariant,
                price:               price,
                discount_percentage: p.discount_percent,
                quantity:            1,
                stock_quantity:      variant ? variant.stock : (p.stock_quantity ?? 999),
                supplier:            p.supplier,
                images:              p.image ? [p.image] : [],
            });
            showToast(`${dispName} added to cart!`);
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        window.dispatchEvent(new Event('cart-updated'));
    }

    // ─── Initial Load ─────────────────────────────────────────────────────────
    renderProducts(allProducts);

})();
</script>
@endpush
