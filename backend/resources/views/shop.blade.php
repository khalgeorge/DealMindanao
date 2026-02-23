@extends('layouts.app')

@section('meta_title', 'Shop Local Deals in Mindanao | Hardware, Food and More – DealMindanao')
@section('meta_description', 'Browse hardware and local products from verified Mindanao sellers. Order online and pay offline via COD or GCash after confirmation.')
@section('canonical', url('/shop'))
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
                        <span class="ml-3 text-sm text-gray-700 group-hover:text-brand-600">{{ $category->name }}</span>
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
                    Verified Mindanao sellers • Offline payment • Manual order confirmation
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
                <p class="text-sm text-gray-500">All prices are indicative. Final pricing and availability are confirmed by our team after order review. Order online, pay offline via COD or GCash.</p>
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

            return `
                <div class="product-card group flex flex-col h-full bg-white animate-fade-in shadow-sm hover:shadow-xl transition-all duration-300 rounded-lg">
                    <div class="block overflow-hidden relative aspect-[4/5] bg-gray-100 rounded-t-lg cursor-pointer"
                         onclick="window.viewProductModal(${p.id})">
                        <img src="${imageUrl}" alt="${p.name} – Buy local in Mindanao""
                             onerror="this.onerror=null;this.src='/images/unknown-product.svg'"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        ${p.is_on_promo ? `
                        <div class="absolute top-3 left-3 bg-brand-600 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-sm">
                            ${p.promo_label || discountPercent + '% OFF'}
                        </div>` : ''}
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">${p.name}</h3>
                        </div>
                        <div class="mt-4 flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold text-gray-900">${formatPrice(salePrice)}</span>
                                ${p.is_on_promo ? `<span class="text-xs text-gray-400 line-through">${formatPrice(p.price)}</span>` : ''}
                            </div>
                            <div class="flex gap-2">
                                <button onclick="window.viewProductModal(${p.id})"
                                        class="btn-primary flex-1 btn-sm text-xs">
                                    <svg class="w-3.5 h-3.5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </button>
                                <button onclick="window.addToCartFromShop(${p.id})"
                                        class="p-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </button>
                            </div>
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

        const salePrice       = p.display_price;
        const discountPercent = p.discount_percent;
        const imageUrl        = p.image || '/images/unknown-product.svg';

        modalContent.innerHTML = `
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                        <img src="${imageUrl}" alt="${p.name} – Buy local in Mindanao" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='/images/unknown-product.svg'">
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">${p.name}</h2>
                        ${p.company ? `<p class="text-sm text-gray-500 mb-2">by ${p.company}</p>` : ''}
                        ${p.is_on_promo ? `
                        <div class="inline-flex items-center gap-2 bg-brand-50 px-3 py-1 rounded-full mb-4">
                            <span class="text-brand-600 font-bold text-sm">${p.promo_label || discountPercent + '% OFF'}</span>
                        </div>` : ''}
                        <div class="flex items-baseline gap-3 mb-6">
                            <span class="text-4xl font-bold text-gray-900">${formatPrice(salePrice)}</span>
                            ${p.is_on_promo ? `<span class="text-xl text-gray-400 line-through">${formatPrice(p.price)}</span>` : ''}
                        </div>
                    </div>
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
    window.addToCartFromShop  = id => addToCart(id);
    window.addToCartFromModal = id => { addToCart(id); closeModal(); };

    function addToCart(productId) {
        const p = allProducts.find(p => p.id == productId);
        if (!p) return;

        const cart     = JSON.parse(localStorage.getItem('cart') || '[]');
        const existing = cart.find(item => item.id == productId);

        if (existing) {
            existing.quantity += 1;
            showToast(`${p.name} quantity updated!`);
        } else {
            const discountPercent = p.discount_percent;
            cart.push({
                id:                  p.id,
                name:                p.name,
                price:               p.price,
                discount_percentage: discountPercent,
                quantity:            1,
                stock_quantity:      999,
                company:             p.company,
                images:              p.image ? [p.image] : [],
            });
            showToast(`${p.name} added to cart!`);
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        window.dispatchEvent(new Event('cart-updated'));
    }

    // ─── Initial Load ─────────────────────────────────────────────────────────
    renderProducts(allProducts);

})();
</script>
@endpush
