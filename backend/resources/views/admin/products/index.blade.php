@extends('layouts.admin')

@section('title', 'Products - DealMindanao Admin')

@section('content')
<header class="admin-header">
  <div>
    <h1 class="text-xl font-black text-gray-900">Products</h1>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Manage Store Inventory</p>
  </div>
  <div class="flex items-center gap-4">
    <button onclick="exportForPartners()" class="btn-secondary flex items-center gap-2">
       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
       Export for Partners
    </button>
    <a href="/admin/products/create" class="btn-primary flex items-center gap-2">
       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
       Add Product
    </a>
  </div>
</header>

<div class="admin-content">
  <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
     <div class="relative w-full md:w-96">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </span>
        <input type="text" id="product-search" name="search" placeholder="Search products, SKUs, or partners..." class="input pl-11" autocomplete="off">
     </div>
     <div class="flex gap-2 w-full md:w-auto">
        <select id="filter-category" name="filter-category" class="input py-2 text-xs font-bold uppercase tracking-wider" autocomplete="off">
           <option value="">All Categories</option>
        </select>
        <select id="filter-status" name="filter-status" class="input py-2 text-xs font-bold uppercase tracking-wider" autocomplete="off">
           <option value="">All Statuses</option>
           <option value="published">Published</option>
           <option value="draft">Draft</option>
           <option value="Active">Active Stock</option>
           <option value="Low">Low Stock</option>
           <option value="Out">Out of Stock</option>
        </select>
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
                 <th>Product</th>
                 <th>Category</th>
                 <th>Partner</th>
                 <th>Price</th>
                 <th>Variants</th>
                 <th>Stock</th>
                 <th>Active</th>
                 <th>Featured</th>
                 <th class="text-right">Actions</th>
              </tr>
           </thead>
           <tbody id="product-list-body">
              <tr class="animate-pulse">
                 <td class="px-6 py-4"><div class="h-10 bg-gray-100 rounded-lg w-40"></div></td>
                 <td class="px-6 py-4"><div class="h-4 bg-gray-50 rounded-lg w-20"></div></td>
                 <td class="px-6 py-4"><div class="h-4 bg-gray-50 rounded-lg w-24"></div></td>
                 <td class="px-6 py-4"><div class="h-4 bg-gray-50 rounded-lg w-16"></div></td>
                 <td class="px-6 py-4"><div class="h-4 bg-gray-50 rounded-lg w-12"></div></td>
                 <td class="px-6 py-4"><div class="h-6 bg-gray-50 rounded-full w-16"></div></td>
                 <td class="px-6 py-4 text-right"><div class="ml-auto h-8 bg-gray-50 rounded-lg w-20"></div></td>
              </tr>
           </tbody>
        </table>
     </div>
     
     <div class="p-6 border-t border-gray-50 flex items-center justify-between">
        <div class="flex items-center gap-4">
           <span id="pagination-info" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Loading...</span>
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
           <button id="prev-page" class="btn-secondary btn-sm">Previous</button>
           <button id="next-page" class="btn-secondary btn-sm">Next</button>
        </div>
     </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>
window.resetFilters = function() {
   document.getElementById('product-search').value = '';
   document.getElementById('filter-category').value = '';
   document.getElementById('filter-status').value = '';
   if (window.currentPage !== undefined) {
     window.currentPage = 1;
   }
   if (typeof renderProducts === 'function') {
     renderProducts();
   }
};
</script>
<script type="module">
const API_URL = '{{ config("app.url") }}/api';
const api = {
  async get(url) {
    const token = localStorage.getItem('auth_token');
    const r = await fetch(API_URL+url, { headers: { 'Authorization': 'Bearer '+token, 'Accept': 'application/json' }});
    return await r.json();
  },
  async post(url, data) {
    const token = localStorage.getItem('auth_token');
    const r = await fetch(API_URL+url, { method: 'POST', headers: { 'Authorization': 'Bearer '+token, 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
    return await r.json();
  },
  async put(url, data) {
    const token = localStorage.getItem('auth_token');
    const r = await fetch(API_URL+url, { method: 'PUT', headers: { 'Authorization': 'Bearer '+token, 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
    return await r.json();
  },
  async delete(url) {
    const token = localStorage.getItem('auth_token');
    const r = await fetch(API_URL+url, { method: 'DELETE', headers: { 'Authorization': 'Bearer '+token }});
    return await r.json();
  }
};

let allProducts = [];
window.currentPage = 1;
let itemsPerPage = 10;

function loadProducts() {
  const raw = @json($products);
  allProducts = raw.map(p => ({
    id: p.id,
    name: p.name,
    slug: p.slug || '',
    cat: p.category?.name || 'Uncategorized',
    partner: p.supplier?.name || 'Unknown',
    srp: parseFloat(p.srp ?? p.price ?? 0),
    supplier_price: parseFloat(p.supplier_price ?? 0),
    brand_id: p.brand?.id || null,
    brand: p.brand?.name || '',
    model_code: p.model_code || '',
    variant: p.variant || '',
    stock: p.stock_quantity || 0,
    stock_quantity: p.stock_quantity || 0,
    is_active: p.is_active !== false,
    is_featured: p.is_featured === true,
    discount: parseFloat(p.discount || 0),
    promo_label: p.promo_label || '',
    promo_starts_at: p.promo_starts_at || '',
    promo_ends_at: p.promo_ends_at || '',
    stock_status: (p.stock_quantity||0) > 10 ? 'Active' : ((p.stock_quantity||0) > 0 ? 'Low' : 'Out'),
    status: p.status || 'draft',
    description: p.description || '',
    specifications: p.specifications || '',
    variants: p.variants || [],
    images: p.images,
    category_id: p.category?.id,
    supplier_id: p.supplier?.id || null
  }));
  renderProducts();
}

async function loadFormData() {
  try {
    const cats = await api.get('/categories');
    const categories = Array.isArray(cats) ? cats : (cats.data || []);
    
    document.getElementById('filter-category').innerHTML = '<option value="">All Categories</option>' + categories.map(c => `<option value="${c.name}">${c.name}</option>`).join('');
    document.querySelector('select[name="category"]').innerHTML = '<option value="">Select Category</option>' + categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
  } catch (e) {}
}

window.duplicateProduct = (id) => {
  const p = allProducts.find(pr => pr.id == id);
  if (!p || !confirm(`Duplicate "${p.name}"? A draft copy will be created and opened for editing.`)) return;
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '{{ url("/admin/products") }}/' + id + '/duplicate';
  const tokenInput = document.createElement('input');
  tokenInput.type = 'hidden';
  tokenInput.name = '_token';
  tokenInput.value = csrfToken;
  form.appendChild(tokenInput);
  document.body.appendChild(form);
  form.submit();
};

window.deleteProduct = async (id) => {
  const p = allProducts.find(pr => pr.id == id);
  if (!p || !confirm(`Delete "${p.name}"?`)) return;
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const response = await fetch('{{ url("/admin/products") }}/' + id, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/x-www-form-urlencoded',
        'Accept': 'application/json'
      },
      body: '_method=DELETE',
      credentials: 'same-origin'
    });
    
    if (response.ok) {
      allProducts = allProducts.filter(pr => pr.id != id);
      renderProducts();
    } else {
      throw new Error('Delete failed');
    }
  } catch (e) {
    console.error('Delete error:', e);
    alert('Failed to delete: ' + e.message);
  }
};

window.renderProducts = () => {
  const body = document.getElementById('product-list-body');
  const search = document.getElementById('product-search').value.toLowerCase();
  const catFilter = document.getElementById('filter-category').value;
  const statusFilter = document.getElementById('filter-status').value;
  
  const filtered = allProducts.filter(p => {
    const matchesSearch = p.name.toLowerCase().includes(search) || p.partner.toLowerCase().includes(search);
    const matchesCat = !catFilter || p.cat === catFilter;
    const matchesStatus = !statusFilter ||
      (['draft','published'].includes(statusFilter) ? p.status === statusFilter : p.stock_status === statusFilter);
    return matchesSearch && matchesCat && matchesStatus;
  });
  
  const total = filtered.length;
  const totalPages = Math.ceil(total / itemsPerPage) || 1;
  if (window.currentPage > totalPages) window.currentPage = totalPages;
  const start = (window.currentPage - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  const items = filtered.slice(start, end);
  
  if (items.length === 0) {
    body.innerHTML = '<tr><td colspan="10" class="px-6 py-8 text-center text-gray-400">No products found</td></tr>';
  } else {
    body.innerHTML = items.map(p => `<tr>
      <td class="px-6 py-4">
        <div class="font-bold">${p.name}</div>
        <span class="inline-block mt-0.5 text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded ${p.status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'}">${p.status}</span>
      </td>
      <td class="px-6 py-4">${p.cat}</td>
      <td class="px-6 py-4 text-xs font-bold text-brand-600 uppercase">${p.partner}</td>
      <td class="px-6 py-4 font-mono">₱${p.srp.toFixed(2)}</td>
      <td class="px-6 py-4">
        ${p.variants?.options?.length > 0
          ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-700">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
              ${p.variants.options.length} ${p.variants.attribute || 'variant'}${p.variants.options.length > 1 ? 's' : ''}
            </span>`
          : `<span class="text-xs text-gray-400">—</span>`
        }
      </td>
      <td class="px-6 py-4">${p.stock}</td>
      <td class="px-6 py-4">
        <button onclick="toggleActive(${p.id})" title="Toggle active"
          class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none ${p.is_active ? 'bg-brand-600' : 'bg-gray-300'}">
          <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform ${p.is_active ? 'translate-x-6' : 'translate-x-1'}"></span>
        </button>
      </td>
      <td class="px-6 py-4">
        <button onclick="toggleFeatured(${p.id})" title="Toggle featured"
          class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none ${p.is_featured ? 'bg-accent-500' : 'bg-gray-300'}">
          <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform ${p.is_featured ? 'translate-x-6' : 'translate-x-1'}"></span>
        </button>
      </td>
      <td class="px-6 py-4">
        <div class="flex items-center justify-end gap-1">
          <a href="/product/${p.slug}" target="_blank" title="View Product"
             class="inline-flex items-center justify-center h-8 rounded-lg text-gray-400 hover:text-brand-600 hover:bg-brand-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          </a>
          <a href="/admin/products/${p.id}/edit" title="Edit Product"
             class="inline-flex items-center justify-center h-8 rounded-lg text-gray-400 hover:text-brand-600 hover:bg-brand-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          </a>
          <button onclick="duplicateProduct(${p.id})" title="Duplicate Product"
             class="inline-flex items-center justify-center h-8 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
          </button>
          <button onclick="deleteProduct(${p.id})" title="Delete Product"
             class="inline-flex items-center justify-center h-8 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          </button>
        </div>
      </td>
    </tr>`).join('');
  }
  
  document.getElementById('pagination-info').textContent = `Showing ${total === 0 ? 0 : start+1}-${Math.min(end, total)} of ${total} products`;
  const prevBtn = document.getElementById('prev-page');
  const nextBtn = document.getElementById('next-page');
  prevBtn.disabled = window.currentPage === 1;
  prevBtn.style.opacity = window.currentPage === 1 ? '0.5' : '1';
  nextBtn.disabled = window.currentPage === totalPages;
  nextBtn.style.opacity = window.currentPage === totalPages ? '0.5' : '1';
};

document.getElementById('product-search').addEventListener('input', () => { window.currentPage=1; renderProducts(); });
document.getElementById('filter-category').addEventListener('change', () => { window.currentPage=1; renderProducts(); });
document.getElementById('filter-status').addEventListener('change', () => { window.currentPage=1; renderProducts(); });
document.getElementById('rows-per-page').addEventListener('change', e => { itemsPerPage=parseInt(e.target.value); window.currentPage=1; renderProducts(); });
document.getElementById('prev-page').addEventListener('click', () => { if (window.currentPage > 1) { window.currentPage--; renderProducts(); }});
document.getElementById('next-page').addEventListener('click', () => { 
  const search = document.getElementById('product-search').value.toLowerCase();
  const catFilter = document.getElementById('filter-category').value;
  const statusFilter = document.getElementById('filter-status').value;
  const filtered = allProducts.filter(p => {
    const matchesSearch = p.name.toLowerCase().includes(search) || p.partner.toLowerCase().includes(search);
    const matchesCat = !catFilter || p.cat === catFilter;
    const matchesStatus = !statusFilter ||
      (['draft','published'].includes(statusFilter) ? p.status === statusFilter : p.stock_status === statusFilter);
    return matchesSearch && matchesCat && matchesStatus;
  });
  if (window.currentPage < Math.ceil(filtered.length / itemsPerPage)) { window.currentPage++; renderProducts(); }
});

window.toggleActive = async (id) => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  try {
    const response = await fetch('{{ url("/admin/products") }}/' + id + '/toggle-status', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json' },
      credentials: 'same-origin'
    });
    if (response.ok) {
      const p = allProducts.find(pr => pr.id == id);
      if (p) { p.is_active = !p.is_active; renderProducts(); }
    }
  } catch(e) { alert('Failed to toggle status'); }
};

window.toggleFeatured = async (id) => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  try {
    const response = await fetch('{{ url("/admin/products") }}/' + id + '/toggle-featured', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/x-www-form-urlencoded', 'Accept': 'application/json' },
      credentials: 'same-origin'
    });
    if (response.ok) {
      const p = allProducts.find(pr => pr.id == id);
      if (p) { p.is_featured = !p.is_featured; renderProducts(); }
    }
  } catch(e) { alert('Failed to toggle featured'); }
};

window.exportForPartners = () => {
  const search = document.getElementById('product-search').value.toLowerCase();
  const catFilter = document.getElementById('filter-category').value;
  const statusFilter = document.getElementById('filter-status').value;

  const filtered = allProducts.filter(p => {
    const matchesSearch = p.name.toLowerCase().includes(search) || p.partner.toLowerCase().includes(search);
    const matchesCat = !catFilter || p.cat === catFilter;
    const matchesStatus = !statusFilter ||
      (['draft','published'].includes(statusFilter) ? p.status === statusFilter : p.stock_status === statusFilter);
    return matchesSearch && matchesCat && matchesStatus;
  });

  if (filtered.length === 0) {
    alert('No products to export.');
    return;
  }

  const now = new Date();
  const datetime = now.getFullYear() + '-' +
    String(now.getMonth()+1).padStart(2,'0') + '-' +
    String(now.getDate()).padStart(2,'0') + '_' +
    String(now.getHours()).padStart(2,'0') + '-' +
    String(now.getMinutes()).padStart(2,'0') + '-' +
    String(now.getSeconds()).padStart(2,'0');

  // Format specifications [{group, items:[{label,value}]}] into readable text
  const formatSpecs = (specs) => {
    if (!specs || !Array.isArray(specs) || specs.length === 0) return '';
    return specs.map(g => {
      const items = (g.items || []).map(i => `${i.label}: ${i.value}`).join(', ');
      return g.group ? `${g.group} — ${items}` : items;
    }).join(' | ');
  };
  const formatVariants = (p) => {
    // Simple product-level variant (e.g. "2M")
    const simple = p.variant || '';
    // Selectable options array [{attribute, options:[{label,price,stock}]}]
    const opts = Array.isArray(p.variants) ? p.variants : [];
    if (opts.length > 0) {
      return opts.map(g =>
        `${g.attribute}: ${(g.options || []).map(o => o.label).join(', ')}`
      ).join(' | ');
    }
    return simple;
  };

  // Build rows array (header + data)
  const sheetData = [
    ['Name', 'Category', 'Brand', 'Model Code', 'Technical Specifications', 'Product Variants / Options'],
    ...filtered.map(p => [
      p.name,
      p.cat,
      p.brand || '',
      p.model_code || '',
      formatSpecs(p.specifications),
      formatVariants(p)
    ])
  ];

  const wb = XLSX.utils.book_new();
  const ws = XLSX.utils.aoa_to_sheet(sheetData);

  // Column widths
  ws['!cols'] = [
    { wch: 40 }, // Name
    { wch: 20 }, // Category
    { wch: 20 }, // Brand
    { wch: 18 }, // Model Code
    { wch: 50 }, // Technical Specifications
    { wch: 45 }, // Product Variants / Options
  ];

  XLSX.utils.book_append_sheet(wb, ws, 'Products');
  XLSX.writeFile(wb, `products_${datetime}.xlsx`);
};

loadProducts();
loadFormData();
</script>
@endpush
