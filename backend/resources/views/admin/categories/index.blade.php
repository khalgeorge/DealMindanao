@extends('layouts.admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900">Categories</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Manage Product Taxonomy</p>
    </div>
    <button onclick="openCategoryModal()" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Category
    </button>
</header>

<div class="admin-content">
    @if(session('success'))
        <div class="alert-success mb-6">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error mb-6">{{ session('error') }}</div>
    @endif

    <!-- Filters & Search -->
    <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" id="category-search" placeholder="Search categories or slugs..." class="input pl-11">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button onclick="resetFilters()" class="btn-secondary px-4 py-2 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 whitespace-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Reset
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th class="text-right">Products</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="category-list">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">{{ $category->slug }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="badge-success text-[10px]">Active</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-black text-gray-700">{{ $category->products_count }}</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 hidden sm:inline">items</span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button onclick="editCategory({{ $category->id }})" class="text-brand-600 font-bold text-xs uppercase hover:underline">Edit</button>
                                <span class="text-gray-300">|</span>
                                <button onclick="deleteCategory({{ $category->id }})" class="text-red-600 font-bold text-xs uppercase hover:underline" @disabled($category->products_count > 0)>Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 font-bold italic">No categories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-6 border-t border-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span id="pagination-info" class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                    Showing {{ $categories->firstItem() ?? 0 }}-{{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} categories
                </span>
                <div class="h-4 w-px bg-gray-200"></div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rows:</span>
                    <select id="rows-per-page" class="bg-transparent border-none text-xs font-black text-gray-900 focus:ring-0 cursor-pointer">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button id="prev-page" class="btn-secondary btn-sm opacity-50 cursor-not-allowed" disabled>Previous</button>
                <button id="next-page" class="btn-secondary btn-sm">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div id="category-modal" class="fixed inset-0 z-[999] hidden" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeCategoryModal()"></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 pointer-events-none flex items-center justify-center p-4">
        <div class="pointer-events-auto bg-white w-full max-w-md rounded-lg shadow-2xl overflow-hidden transform transition-all">
            <form id="category-form" class="flex flex-col">
                @csrf
                <input type="hidden" id="category-id" value="">
                <input type="hidden" id="category-method" value="POST">
                <div class="p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h3 id="modal-title" class="text-2xl font-black text-gray-900 uppercase tracking-tighter">New Category</h3>
                        <button type="button" onclick="closeCategoryModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-6 pb-4">
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Category Name</label>
                            <input type="text" id="category-name" name="name" required placeholder="e.g. Fresh Fruits" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
                            <p class="text-xs text-gray-400 mt-1">Slug will be auto-generated from the name</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-8 py-6 flex flex-row-reverse gap-3 border-t border-gray-100">
                    <button type="submit" class="btn-primary px-8 py-3 rounded-lg font-bold">Save Category</button>
                    <button type="button" onclick="closeCategoryModal()" class="px-8 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let allCategories = @json($categories->items());
let currentPage = 1;
let itemsPerPage = 10;

// Global Modal Functions
window.openCategoryModal = () => {
    const modal = document.getElementById('category-modal');
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('category-form');
    
    if (modal) {
        modalTitle.innerText = 'New Category';
        form.reset();
        document.getElementById('category-id').value = '';
        document.getElementById('category-method').value = 'POST';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        modal.style.display = 'block';
    }
};

window.closeCategoryModal = () => {
    const modal = document.getElementById('category-modal');
    const form = document.getElementById('category-form');
    
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    if (form) form.reset();
};

window.editCategory = (id) => {
    const category = allCategories.find(c => c.id === id);
    if (!category) return;
    
    const modal = document.getElementById('category-modal');
    const modalTitle = document.getElementById('modal-title');
    
    modalTitle.innerText = 'Edit Category';
    document.getElementById('category-id').value = id;
    document.getElementById('category-method').value = 'PUT';
    document.getElementById('category-name').value = category.name;
    
    modal.classList.remove('hidden');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
};

window.deleteCategory = async (id) => {
    const category = allCategories.find(c => c.id === id);
    if (!category) return;
    
    if (!confirm(`Delete category "${category.name}"? This will fail if products exist in this category.`)) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        if (response.ok) {
            window.location.reload();
        } else {
            const data = await response.json();
            alert(data.message || 'Failed to delete category');
        }
    } catch (error) {
        console.error('Error deleting category:', error);
        alert('Failed to delete category');
    }
};

// Form Submit
document.getElementById('category-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const id = document.getElementById('category-id').value;
    const method = document.getElementById('category-method').value;
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    const formData = new FormData();
    formData.append('name', document.getElementById('category-name').value);
    formData.append('_token', document.querySelector('input[name="_csrf"]')?.value || document.querySelector('meta[name="csrf-token"]').content);
    
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    submitBtn.disabled = true;
    submitBtn.textContent = id ? 'Updating...' : 'Saving...';
    
    try {
        const url = id ? `/admin/categories/${id}` : '/admin/categories';
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });
        
        if (response.ok) {
            window.location.reload();
        } else {
            const data = await response.json();
            alert(data.message || 'Failed to save category');
        }
    } catch (error) {
        console.error('Error saving category:', error);
        alert('Failed to save category');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});

// Handle ESC key to close
window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        window.closeCategoryModal();
    }
});

// Reset filters function
window.resetFilters = () => {
    const searchInput = document.getElementById('category-search');
    if (searchInput) {
        searchInput.value = '';
        currentPage = 1;
        renderCategories();
    }
};

// Render categories with pagination
function renderCategories() {
    const searchTerm = document.getElementById('category-search').value.toLowerCase();
    const tbody = document.getElementById('category-list');
    const paginationInfo = document.getElementById('pagination-info');
    const prevBtn = document.getElementById('prev-page');
    const nextBtn = document.getElementById('next-page');
    
    // Filter categories
    const filtered = allCategories.filter(cat => {
        const name = cat.name.toLowerCase();
        const slug = cat.slug.toLowerCase();
        return name.includes(searchTerm) || slug.includes(searchTerm);
    });
    
    // Calculate pagination
    const totalItems = filtered.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedItems = filtered.slice(start, end);
    
    // Render table rows
    if (paginatedItems.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 font-bold italic">No categories found matching your search</td></tr>';
    } else {
        tbody.innerHTML = paginatedItems.map(cat => `
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-900">${cat.name}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">${cat.slug}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="badge-success text-[10px]">Active</span>
                </td>
                <td class="px-6 py-4 text-right">
                    <span class="text-sm font-black text-gray-700">${cat.products_count || 0}</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 hidden sm:inline">items</span>
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <button onclick="editCategory(${cat.id})" class="text-brand-600 font-bold text-xs uppercase hover:underline">Edit</button>
                    <span class="text-gray-300">|</span>
                    <button onclick="deleteCategory(${cat.id})" class="text-red-600 font-bold text-xs uppercase hover:underline" ${cat.products_count > 0 ? 'disabled' : ''}>Delete</button>
                </td>
            </tr>
        `).join('');
    }
    
    // Update pagination info
    const currentEnd = Math.min(end, totalItems);
    paginationInfo.textContent = `Showing ${totalItems === 0 ? 0 : start + 1}-${currentEnd} of ${totalItems} categories`;
    
    // Update pagination buttons
    if (currentPage === 1) {
        prevBtn.disabled = true;
        prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
        prevBtn.onclick = null;
    } else {
        prevBtn.disabled = false;
        prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        prevBtn.onclick = (e) => {
            e.preventDefault();
            currentPage--;
            renderCategories();
        };
    }
    
    if (currentPage >= totalPages) {
        nextBtn.disabled = true;
        nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
        nextBtn.onclick = null;
    } else {
        nextBtn.disabled = false;
        nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        nextBtn.onclick = (e) => {
            e.preventDefault();
            currentPage++;
            renderCategories();
        };
    }
}

// Client-side search functionality
document.getElementById('category-search')?.addEventListener('input', function(e) {
    currentPage = 1;
    renderCategories();
});

// Rows per page selector
document.getElementById('rows-per-page')?.addEventListener('change', function(e) {
    itemsPerPage = parseInt(e.target.value);
    currentPage = 1;
    renderCategories();
});

// Initial render
renderCategories();
</script>
@endpush
