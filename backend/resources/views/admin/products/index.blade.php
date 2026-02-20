@extends('layouts.admin')

@section('title', 'Products - DealMindanao Admin')

@section('content')
<header class="admin-header">
  <div>
    <h1 class="text-xl font-black text-gray-900">Products</h1>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Manage Store Inventory</p>
  </div>
  <div class="flex items-center gap-4">
    <button onclick="openProductModal()" class="btn-primary flex items-center gap-2">
       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
       Add Product
    </button>
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
           <option value="Active">Active</option>
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

@push('modals')
<div id="product-modal" class="fixed inset-0 z-[999] hidden" role="dialog" aria-modal="true" style="opacity: 0; transition: opacity 0.3s ease-in-out;">
   <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeProductModal()"></div>
   <div class="fixed inset-0 pointer-events-none flex items-center justify-center p-4">
      <div class="pointer-events-auto bg-white w-full max-w-2xl rounded-lg shadow-2xl overflow-hidden transform transition-all duration-300" style="transform: scale(0.95); opacity: 0;">
         <form id="product-form" class="flex flex-col max-h-[90vh]">
            <input type="hidden" name="id" id="product-id">
            <div class="p-8 overflow-y-auto">
               <div class="flex justify-between items-center mb-8">
                  <h3 id="modal-title" class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Add New Product</h3>
                  <button type="button" onclick="closeProductModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                  </button>
               </div>
               
               <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-4">
                  <div class="md:col-span-2">
                     <label for="product-name" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Product Name</label>
                     <input type="text" id="product-name" name="name" required placeholder="e.g. Premium Davao Durian" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900" autocomplete="off">
                  </div>
                  <div>
                     <label for="product-category" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Category</label>
                     <select id="product-category" name="category" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900" autocomplete="off">
                        <option value="">Select Category</option>
                     </select>
                  </div>
                  <div>
                     <label for="product-partner" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Partner / Vendor</label>
                     <select id="product-partner" name="partner" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900" autocomplete="off">
                        <option value="">Select Partner</option>
                     </select>
                  </div>
                  <div>
                     <label for="product-price" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Price (₱)</label>
                     <input type="number" id="product-price" name="price" required step="0.01" placeholder="250.00" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900" autocomplete="off">
                  </div>
                  <div>
                     <label for="product-stock" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Stock Level</label>
                     <input type="number" id="product-stock" name="stock_quantity" required placeholder="100" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900" autocomplete="off">
                  </div>
                  <div class="md:col-span-2">
                     <label for="product-description" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Description</label>
                     <textarea id="product-description" name="description" rows="3" placeholder="Detailed product description..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900" autocomplete="off"></textarea>
                  </div>
                  <div class="md:col-span-2 flex gap-6">
                     <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="product-is-active" name="is_active" value="1" checked class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        <span class="text-sm font-semibold text-gray-700">Active <span class="text-gray-400 font-normal">(visible to customers)</span></span>
                     </label>
                     <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="product-is-featured" name="is_featured" value="1" class="w-4 h-4 rounded border-gray-300 text-accent-500 focus:ring-accent-400">
                        <span class="text-sm font-semibold text-gray-700">Featured <span class="text-gray-400 font-normal">(show on homepage)</span></span>
                     </label>
                  </div>
                  <div class="md:col-span-2">
                     <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3">Product Images</label>
                     
                     <!-- Image Upload Area -->
                     <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-brand-500 transition-colors mb-4">
                        <input type="file" id="image-file-input" accept="image/jpeg,image/jpg,image/png,image/webp" multiple class="hidden">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        <button type="button" onclick="document.getElementById('image-file-input').click()" class="btn-primary mb-2">Choose Images</button>
                        <p class="text-xs text-gray-500">or drag and drop images here</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP up to 2MB each</p>
                     </div>
                     
                     <!-- Image Preview Grid -->
                     <div id="image-preview-grid" class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4"></div>
                     
                     <!-- Optional: URL Input -->
                     <div class="border-t border-gray-200 pt-4">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Or enter image URL</label>
                        <div id="image-url-container" class="space-y-2">
                           <div class="flex gap-2">
                              <input type="url" name="image-url-1" placeholder="https://example.com/image.jpg" class="flex-1 px-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
                              <button type="button" onclick="removeUrlInput(this)" class="text-red-500 hover:text-red-700 transition-colors px-3">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                              </button>
                           </div>
                        </div>
                        <button type="button" onclick="addUrlInput()" class="mt-2 text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                           Add Another URL
                        </button>
                     </div>
                     
                     <!-- Hidden input to store uploaded image paths -->
                     <input type="hidden" id="uploaded-images" name="uploaded_images" value="">
                  </div>
               </div>
            </div>
            
            <div class="bg-gray-50 px-8 py-6 flex flex-row-reverse gap-3 border-t border-gray-100">
               <button type="submit" class="btn-primary px-8 py-3 rounded-lg font-bold">Save Product</button>
               <button type="button" onclick="closeProductModal()" class="px-8 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">Cancel</button>
            </div>
         </form>
      </div>
   </div>
</div>
@endpush

@push('scripts')
<script>
// Uploaded images array
let uploadedImages = [];

// File upload handling
document.getElementById('image-file-input').addEventListener('change', async function(e) {
   const files = Array.from(e.target.files);
   await uploadImages(files);
   this.value = ''; // Reset input for re-upload
});

// Drag and drop
const dropZone = document.querySelector('.border-dashed');
dropZone.addEventListener('dragover', (e) => {
   e.preventDefault();
   dropZone.classList.add('border-brand-500', 'bg-brand-50');
});
dropZone.addEventListener('dragleave', () => {
   dropZone.classList.remove('border-brand-500', 'bg-brand-50');
});
dropZone.addEventListener('drop', async (e) => {
   e.preventDefault();
   dropZone.classList.remove('border-brand-500', 'bg-brand-50');
   const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
   await uploadImages(files);
});

async function uploadImages(files) {
   const previewGrid = document.getElementById('image-preview-grid');
   
   for (const file of files) {
      // Create preview container
      const previewDiv = document.createElement('div');
      previewDiv.className = 'relative group';
      previewDiv.innerHTML = `
         <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-200">
            <img src="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
               <div class="text-white text-sm">Uploading...</div>
            </div>
         </div>
      `;
      previewGrid.appendChild(previewDiv);
      
      // Show local preview
      const img = previewDiv.querySelector('img');
      const reader = new FileReader();
      reader.onload = (e) => img.src = e.target.result;
      reader.readAsDataURL(file);
      
      try {
         // Upload to server
         const formData = new FormData();
         formData.append('image', file);
         
         const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
         if (!csrfToken) {
            throw new Error('CSRF token not found');
         }
         
         const response = await fetch('{{ route("admin.products.uploadImage") }}', {
            method: 'POST',
            body: formData,
            headers: {
               'X-CSRF-TOKEN': csrfToken,
               'Accept': 'application/json'
            },
            credentials: 'same-origin'
         });
         
         // Get response text first to check what we received
         const responseText = await response.text();
         
         if (!response.ok) {
            console.error('Server response:', responseText);
            
            // Try to parse error as JSON for validation errors
            try {
               const errorData = JSON.parse(responseText);
               if (errorData.errors) {
                  const errorMessages = Object.values(errorData.errors).flat().join(', ');
                  throw new Error(errorMessages);
               }
               if (errorData.message) {
                  throw new Error(errorData.message);
               }
            } catch (parseError) {
               // Not JSON, use generic error
            }
            
            throw new Error(`HTTP error! status: ${response.status}`);
         }
         
         // Try to parse as JSON
         let result;
         try {
            result = JSON.parse(responseText);
         } catch (e) {
            console.error('Invalid JSON response:', responseText);
            throw new Error('Server returned invalid response (not JSON)');
         }
         
         if (result.success) {
            uploadedImages.push(result.path);
            document.getElementById('uploaded-images').value = uploadedImages.join(',');
            
            // Update preview with delete button
            previewDiv.innerHTML = `
               <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-200 relative">
                  <img src="${result.path}" class="w-full h-full object-cover">
                  <button type="button" onclick="removeUploadedImage(this, '${result.path}')" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                  </button>
               </div>
            `;
         } else {
            previewDiv.remove();
            console.error('Upload failed:', result);
            const errorMsg = result.errors ? Object.values(result.errors).flat().join(', ') : (result.message || 'Unknown error');
            alert('Failed to upload image: ' + errorMsg);
         }
      } catch (error) {
         previewDiv.remove();
         console.error('Upload error:', error);
         alert('Failed to upload image: ' + error.message);
      }
   }
}

window.removeUploadedImage = function(button, path) {
   uploadedImages = uploadedImages.filter(p => p !== path);
   document.getElementById('uploaded-images').value = uploadedImages.join(',');
   button.closest('.relative.group').remove();
};

window.addUrlInput = function() {
   const container = document.getElementById('image-url-container');
   const currentCount = container.querySelectorAll('input[type="url"]').length;
   const newIndex = currentCount + 1;
   
   const newInput = document.createElement('div');
   newInput.className = 'flex gap-2';
   newInput.innerHTML = `
      <input type="url" name="image-url-${newIndex}" placeholder="https://example.com/image${newIndex}.jpg" class="flex-1 px-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
      <button type="button" onclick="removeUrlInput(this)" class="text-red-500 hover:text-red-700 transition-colors px-3">
         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
      </button>
   `;
   container.appendChild(newInput);
};

window.removeUrlInput = function(button) {
   const container = document.getElementById('image-url-container');
   const inputGroups = container.querySelectorAll('.flex');
   
   if (inputGroups.length > 1) {
      button.closest('.flex').remove();
   } else {
      container.querySelector('input[type="url"]').value = '';
   }
};

window.openProductModal = function() {
   const modal = document.getElementById('product-modal');
   if (modal) {
      modal.classList.remove('hidden');
      // Trigger reflow to enable transition
      modal.offsetHeight;
      modal.style.opacity = '1';
      document.body.style.overflow = 'hidden';
      
      // Animate modal content
      const modalContent = modal.querySelector('.pointer-events-auto');
      if (modalContent) {
         modalContent.style.transform = 'scale(1)';
         modalContent.style.opacity = '1';
      }
   }
};

window.closeProductModal = function() {
   const modal = document.getElementById('product-modal');
   const form = document.getElementById('product-form');
   
   if (modal) {
      modal.style.opacity = '0';
      const modalContent = modal.querySelector('.pointer-events-auto');
      if (modalContent) {
         modalContent.style.transform = 'scale(0.95)';
         modalContent.style.opacity = '0';
      }
      
      // Wait for animation before hiding
      setTimeout(() => {
         modal.classList.add('hidden');
         document.body.style.overflow = '';
      }, 300);
   }
   
   if (form) {
      form.reset();
      document.getElementById('product-id').value = '';
      document.getElementById('modal-title').textContent = 'Add New Product';
      form.querySelector('button[type="submit"]').textContent = 'Save Product';
      
      // Reset uploaded images
      uploadedImages = [];
      document.getElementById('uploaded-images').value = '';
      document.getElementById('image-preview-grid').innerHTML = '';
      document.getElementById('product-is-active').checked   = true;
      document.getElementById('product-is-featured').checked = false;
      
      // Reset URL inputs to just one
      const container = document.getElementById('image-url-container');
      if (container) {
        container.innerHTML = `
           <div class="flex gap-2">
              <input type="url" name="image-url-1" placeholder="https://example.com/image.jpg" class="flex-1 px-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
              <button type="button" onclick="removeUrlInput(this)" class="text-red-500 hover:text-red-700 transition-colors px-3">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
              </button>
           </div>
        `;
      }
   }
};

window.addImageInput = function() {
   // Deprecated - use addUrlInput instead
   addUrlInput();
};

window.removeImageInput = function(button) {
   // Deprecated - use removeUrlInput instead
   removeUrlInput(button);
};

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

async function loadProducts() {
  try {
    const r = await api.get('/products');
    const data = Array.isArray(r) ? r : (r.data || []);
    allProducts = data.map(p => ({
      id: p.id,
      name: p.name,
      cat: p.category?.name || 'Uncategorized',
      partner: p.company?.name || 'Unknown',
      price: parseFloat(p.price),
      stock: p.stock_quantity || 0,
      stock_quantity: p.stock_quantity || 0,
      is_active: p.is_active !== false,
      is_featured: p.is_featured === true,
      status: (p.stock_quantity||0) > 10 ? 'Active' : ((p.stock_quantity||0) > 0 ? 'Low' : 'Out'),
      description: p.description || '',
      images: p.images,
      category_id: p.category?.id,
      company_id: p.company?.id
    }));
    renderProducts();
  } catch (e) {
    document.getElementById('product-list-body').innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-red-500">Failed to load products</td></tr>';
  }
}

async function loadFormData() {
  try {
    const [cats, comps] = await Promise.all([api.get('/categories'), api.get('/companies')]);
    const categories = Array.isArray(cats) ? cats : (cats.data || []);
    const companies = Array.isArray(comps) ? comps : (comps.data || []);
    
    document.getElementById('filter-category').innerHTML = '<option value="">All Categories</option>' + categories.map(c => `<option value="${c.name}">${c.name}</option>`).join('');
    document.querySelector('select[name="category"]').innerHTML = '<option value="">Select Category</option>' + categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
    document.querySelector('select[name="partner"]').innerHTML = '<option value="">Select Partner</option>' + companies.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
  } catch (e) {}
}

function collectImageUrls() {
  const inputs = document.querySelectorAll('#image-inputs-container input[type="url"]');
  const urls = [];
  inputs.forEach(inp => { if (inp.value.trim()) urls.push(inp.value.trim()); });
  return urls.length > 0 ? urls : null;
}

window.editProduct = (id) => {
  const p = allProducts.find(pr => pr.id == id);
  if (!p) return;
  document.getElementById('product-id').value = p.id;
  document.getElementById('modal-title').textContent = 'Edit Product';
  const form = document.getElementById('product-form');
  form.elements['name'].value = p.name;
  form.elements['category'].value = p.category_id || '';
  form.elements['partner'].value = p.company_id || '';
  form.elements['price'].value = p.price;
  form.elements['stock_quantity'].value = p.stock_quantity || p.stock || 0;
  form.elements['description'].value = p.description || '';
  document.getElementById('product-is-active').checked   = p.is_active !== false;
  document.getElementById('product-is-featured').checked = p.is_featured === true;
  
  // Handle existing images
  const imgs = p.images || [];
  const previewGrid = document.getElementById('image-preview-grid');
  const urlContainer = document.getElementById('image-url-container');
  
  // Reset
  previewGrid.innerHTML = '';
  urlContainer.innerHTML = '';
  uploadedImages = [];
  
  if (imgs.length > 0) {
    imgs.forEach((url, i) => {
      // Check if it's an uploaded image (starts with /storage/) or external URL
      if (url.startsWith('/storage/')) {
        // Show in preview grid as uploaded image
        const previewDiv = document.createElement('div');
        previewDiv.className = 'relative group';
        previewDiv.innerHTML = `
          <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-200 relative">
            <img src="${url}" class="w-full h-full object-cover">
            <button type="button" onclick="removeUploadedImage(this, '${url}')" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>
        `;
        previewGrid.appendChild(previewDiv);
        uploadedImages.push(url);
      } else {
        // Show in URL container
        const urlDiv = document.createElement('div');
        urlDiv.className = 'flex gap-2';
        urlDiv.innerHTML = `
          <input type="url" name="image-url-${i+1}" value="${url}" class="flex-1 px-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
          <button type="button" onclick="removeUrlInput(this)" class="text-red-500 hover:text-red-700 transition-colors px-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        `;
        urlContainer.appendChild(urlDiv);
      }
    });
  }
  
  // If no URL inputs, add one empty one
  if (urlContainer.children.length === 0) {
    urlContainer.innerHTML = `
      <div class="flex gap-2">
        <input type="url" name="image-url-1" placeholder="https://example.com/image.jpg" class="flex-1 px-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
        <button type="button" onclick="removeUrlInput(this)" class="text-red-500 hover:text-red-700 transition-colors px-3">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>
    `;
  }
  
  // Update hidden input
  document.getElementById('uploaded-images').value = uploadedImages.join(',');
  
  openProductModal();
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
      alert('Product deleted successfully!');
      loadProducts();
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
    const matchesStatus = !statusFilter || p.status === statusFilter;
    return matchesSearch && matchesCat && matchesStatus;
  });
  
  const total = filtered.length;
  const totalPages = Math.ceil(total / itemsPerPage) || 1;
  if (window.currentPage > totalPages) window.currentPage = totalPages;
  const start = (window.currentPage - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  const items = filtered.slice(start, end);
  
  if (items.length === 0) {
    body.innerHTML = '<tr><td colspan="9" class="px-6 py-8 text-center text-gray-400">No products found</td></tr>';
  } else {
    body.innerHTML = items.map(p => `<tr>
      <td class="px-6 py-4 font-bold">${p.name}</td>
      <td class="px-6 py-4">${p.cat}</td>
      <td class="px-6 py-4 text-xs font-bold text-brand-600 uppercase">${p.partner}</td>
      <td class="px-6 py-4 font-mono">₱${p.price}</td>
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
      <td class="px-6 py-4 text-right"><button onclick="editProduct(${p.id})" class="text-gray-400 hover:text-brand-600 font-bold text-xs uppercase">Edit</button> | <button onclick="deleteProduct(${p.id})" class="text-gray-400 hover:text-red-600 font-bold text-xs uppercase">Delete</button></td>
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
    const matchesStatus = !statusFilter || p.status === statusFilter;
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

loadProducts();
loadFormData();

document.getElementById('product-form').addEventListener('submit', async e => {
  e.preventDefault();
  const form = e.target;
  const id = form.elements['id']?.value;
  
  // Collect form data
  const formData = new FormData();
  formData.append('name', form.elements['name'].value);
  formData.append('category_id', form.elements['category'].value);
  formData.append('company_id', form.elements['partner'].value);
  formData.append('price', form.elements['price'].value);
  formData.append('stock_quantity', form.elements['stock_quantity'].value || '0');
  formData.append('description', form.elements['description'].value || '');
  formData.append('is_active',   document.getElementById('product-is-active').checked   ? '1' : '0');
  formData.append('is_featured', document.getElementById('product-is-featured').checked ? '1' : '0');
  
  // Add uploaded images
  const uploadedImagesPaths = document.getElementById('uploaded-images').value;
  if (uploadedImagesPaths) {
    formData.append('uploaded_images', uploadedImagesPaths);
  }
  
  // Add URL images
  const urlInputs = document.querySelectorAll('#image-url-container input[type="url"]');
  urlInputs.forEach((input, index) => {
    if (input.value.trim()) {
      formData.append(`images[${index}]`, input.value.trim());
    }
  });
  
  // Add CSRF token
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  if (csrfToken) {
    formData.append('_token', csrfToken);
  }
  
  if (!formData.get('category_id') || !formData.get('company_id')) {
    alert('Please select category and partner');
    return;
  }
  
  try {
    let url, method;
    if (id) {
      url = '{{ url("/admin/products") }}/' + id;
      method = 'POST';
      formData.append('_method', 'PUT');
    } else {
      url = '{{ route("admin.products.store") }}';
      method = 'POST';
    }
    
    const response = await fetch(url, {
      method: method,
      body: formData,
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    });
    
    if (response.ok) {
      alert(id ? 'Product updated successfully!' : 'Product created successfully!');
      window.location.reload();
    } else {
      const error = await response.text();
      console.error('Server error:', error);
      throw new Error('Failed to save');
    }
  } catch (e) {
    console.error('Save error:', e);
    alert('Failed to save product: ' + e.message);
  }
});
</script>
@endpush
