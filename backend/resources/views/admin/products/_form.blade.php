{{--
    Shared product form partial.
    Variables expected:
      $mode    – 'create' | 'edit'
      $product – Product model (edit mode) or null (create mode)
--}}
@php
    $isEdit  = $mode === 'edit';
    $product = $product ?? null;
    $action  = $isEdit
        ? route('admin.products.update', $product->id)
        : route('admin.products.store');
@endphp

{{-- Validation errors --}}
@if(session('success'))
  <div class="mb-6 px-6 py-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm font-semibold">
    {{ session('success') }}
  </div>
@endif

@if($errors->any())
  <div class="mb-6 px-6 py-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
    <p class="font-semibold mb-2">Please fix the following errors:</p>
    <ul class="list-disc list-inside space-y-1">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
  <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="p-8">
    @csrf
    @if($isEdit)
      @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      {{-- Product Name --}}
      <div class="md:col-span-2">
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
          Product Name <span class="font-normal normal-case text-red-500">*</span>
        </label>
        <input type="text" name="name" required
               value="{{ old('name', $product->name ?? '') }}"
               placeholder="e.g. Premium Davao Durian"
               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
        @error('name')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Brand --}}
      <div>
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
          Brand <span class="font-normal normal-case text-gray-400">(optional)</span>
        </label>
        <select name="brand_id"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
          <option value="">— No Brand —</option>
          @foreach($brands as $brand)
            <option value="{{ $brand->id }}"
              @selected(old('brand_id', $product->brand_id ?? '') == $brand->id)>
              {{ $brand->name }}
            </option>
          @endforeach
        </select>
        @error('brand_id')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Model Code --}}
      <div>
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
          Model Code <span class="font-normal normal-case text-gray-400">(optional)</span>
        </label>
        <input type="text" name="model_code"
               value="{{ old('model_code', $product->model_code ?? '') }}"
               placeholder="e.g. MF-DURIAN-500G"
               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
        <p class="text-xs text-gray-400 mt-1">Must be unique per partner.</p>
        @error('model_code')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Variant --}}
      <div>
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
          Variant <span class="font-normal normal-case text-gray-400">(optional)</span>
        </label>
        <input type="text" name="variant"
               value="{{ old('variant', $product->variant ?? '') }}"
               placeholder="e.g. 500g, Red, XL"
               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
        @error('variant')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Category --}}
      <div>
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
          Category <span class="font-normal normal-case text-red-500">*</span>
        </label>
        <select name="category_id" required
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
          <option value="">Select Category</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}"
              @selected(old('category_id', $product->category_id ?? '') == $category->id)>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
        @error('category_id')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Partner --}}
      <div>
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
          Partner <span class="font-normal normal-case text-red-500">*</span>
        </label>
        <select name="supplier_id" required
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
          <option value="">Select Partner</option>
          @foreach($suppliers as $supplier)
            <option value="{{ $supplier->id }}"
              @selected(old('supplier_id', $product->supplier_id ?? '') == $supplier->id)>
              {{ $supplier->name }}
            </option>
          @endforeach
        </select>
        @error('supplier_id')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Partner Cost --}}
      <div>
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
          Partner Cost (₱) <span class="font-normal normal-case text-red-500">*</span>
        </label>
        <input type="number" name="supplier_price" id="supplier-price"
               required step="0.01" min="0.01"
               value="{{ old('supplier_price', $product->supplier_price ?? '') }}"
               placeholder="0.00"
               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
        <p class="text-xs text-gray-400 mt-1">What you pay the partner. Never shown to customers.</p>
        @error('supplier_price')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Selling Price / SRP --}}
      <div>
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
          Selling Price / SRP (₱)
          <span id="srp-auto-badge" class="ml-1 text-xs font-normal normal-case text-brand-600 hidden">auto-computed</span>
        </label>
        <input type="number" name="srp" id="srp-price"
               step="0.01" min="0"
               value="{{ old('srp', $isEdit ? ($product->srp ?? $product->price) : '') }}"
               placeholder="Leave blank to auto-compute (+{{ round(config('products.default_margin') * 100) }}% margin)"
               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
        <p class="text-xs text-gray-400 mt-1">The price customers see. If blank, computed as cost + {{ round(config('products.default_margin') * 100) }}%.</p>
        @error('srp')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Stock Level --}}
      <div>
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Stock Level</label>
        <input type="number" name="stock_quantity"
               value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}"
               placeholder="0"
               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
        @error('stock_quantity')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Description --}}
      <div class="md:col-span-2">
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Description</label>
        <textarea name="description" rows="4"
                  placeholder="Detailed product description..."
                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Technical Specifications --}}
      <div class="md:col-span-2 border-t border-gray-100 pt-6">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Technical Specifications</p>
            <p class="text-xs text-gray-400 mt-1">Add grouped specs e.g. Housing, Switches, Sockets, Indicator.</p>
          </div>
          <button type="button" id="add-spec-group"
                  class="flex items-center gap-1.5 text-xs font-bold text-brand-600 hover:text-brand-700 border border-brand-200 hover:border-brand-400 px-3 py-2 rounded-lg transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Group
          </button>
        </div>
        <div id="specs-builder" class="space-y-3"></div>
        <input type="hidden" name="specifications" id="specs-json-input">
      </div>

      {{-- Product Variants --}}
      <div class="md:col-span-2 border-t border-gray-100 pt-6">
        <div class="flex items-center justify-between mb-1">
          <div>
            <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Product Variants / Options</p>
            <p class="text-xs text-gray-400 mt-1">e.g. Cable Length: 3M at ₱312.50 / 5M at ₱450.00. Leave empty if not applicable.</p>
          </div>
        </div>
        <div id="variants-builder" class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
          <div class="p-3 bg-gray-50 border-b border-gray-200">
            <label class="block text-xs font-semibold text-gray-600 mb-1">Attribute Name <span class="font-normal text-gray-400">(e.g. Cable Length, Size, Color)</span></label>
            <input type="text" id="variant-attribute" name="variant_attribute"
                   value="{{ old('variant_attribute', $product->variants['attribute'] ?? '') }}"
                   placeholder="e.g. Cable Length"
                   class="w-full max-w-xs px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-500 outline-none">
          </div>
          <div class="p-3">
            <div class="grid grid-cols-12 gap-2 mb-2">
              <span class="col-span-4 text-[10px] font-bold uppercase tracking-wider text-gray-400">Option Label</span>
              <span class="col-span-2 text-[10px] font-bold uppercase tracking-wider text-gray-400">Cost (₱)</span>
              <span class="col-span-3 text-[10px] font-bold uppercase tracking-wider text-gray-400">Price (₱) <span class="normal-case font-normal text-brand-500">+{{ round(config('products.default_margin') * 100) }}%</span></span>
              <span class="col-span-2 text-[10px] font-bold uppercase tracking-wider text-gray-400">Stock</span>
              <span class="col-span-1"></span>
            </div>
            <div id="variant-rows" class="space-y-2"></div>
            <button type="button" id="add-variant-row"
                    class="mt-3 text-xs text-brand-600 hover:text-brand-700 font-semibold flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              Add Option
            </button>
          </div>
        </div>
        <input type="hidden" name="variants" id="variants-json-input">
      </div>

      {{-- Promo / Discount Settings --}}
      <div class="md:col-span-2">
        <div class="border border-gray-200 rounded-lg p-5 bg-gray-50">
          <div class="flex items-center justify-between mb-4">
            <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest">Promo / Discount Settings</h4>
            <span id="promo-status-chip" class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-200 text-gray-500">&mdash; No Promo</span>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Discount Amount (₱)</label>
              <input type="number" name="discount" id="promo-discount" step="0.01" min="0"
                     value="{{ old('discount', $product->discount ?? '') }}"
                     placeholder="0.00"
                     class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
              <p class="text-xs text-gray-400 mt-1">Must be less than the selling price. Leave blank for no discount.</p>
              @error('discount')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
                Promo Label <span class="font-normal normal-case text-gray-400">(optional)</span>
              </label>
              <input type="text" name="promo_label" maxlength="60"
                     value="{{ old('promo_label', $product->promo_label ?? '') }}"
                     placeholder="e.g. Flash Sale, Clearance"
                     class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
              <p class="text-xs text-gray-400 mt-1">Short badge text shown on the product card.</p>
              @error('promo_label')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
                Starts At <span class="font-normal normal-case text-gray-400">(optional)</span>
              </label>
              <input type="datetime-local" name="promo_starts_at" id="promo-starts"
                     value="{{ old('promo_starts_at', $isEdit && $product->promo_starts_at ? $product->promo_starts_at->format('Y-m-d\TH:i') : '') }}"
                     class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
              <p class="text-xs text-gray-400 mt-1">Leave blank to activate immediately.</p>
              @error('promo_starts_at')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
                Ends At <span class="font-normal normal-case text-gray-400">(optional)</span>
              </label>
              <input type="datetime-local" name="promo_ends_at" id="promo-ends"
                     value="{{ old('promo_ends_at', $isEdit && $product->promo_ends_at ? $product->promo_ends_at->format('Y-m-d\TH:i') : '') }}"
                     class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
              <p class="text-xs text-gray-400 mt-1">Leave blank for no expiry.</p>
              @error('promo_ends_at')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>
      </div>

      {{-- Product Images --}}
      <div class="md:col-span-2">
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Product Images</label>

        {{-- Hidden field carries comma-separated stored paths to the controller --}}
        <input type="hidden" name="uploaded_images" id="uploaded-images-input"
               value="{{ implode(',', $isEdit ? ($product->images ?? []) : []) }}">

        {{-- Existing image previews (pre-populated in edit mode) --}}
        <div id="image-preview-grid" class="flex flex-wrap gap-3 mb-4">
          @if($isEdit)
            @foreach($product->images ?? [] as $img)
              <div class="relative group">
                <img src="{{ $img }}" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                <button type="button" data-path="{{ $img }}"
                        class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 hover:bg-red-600 text-white rounded-full text-xs flex items-center justify-center leading-none remove-img">✕</button>
              </div>
            @endforeach
          @endif
        </div>

        {{-- Drop zone --}}
        <div id="image-dropzone"
             class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-brand-500 transition-colors bg-gray-50"
             onclick="document.getElementById('image-file-input').click()">
          <svg class="mx-auto mb-3 w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <p class="text-sm font-semibold text-gray-600">
            {{ $isEdit ? 'Click or drag images here to add more' : 'Click or drag images here' }}
          </p>
          <p class="text-xs text-gray-400 mt-1">JPEG, PNG, WebP, GIF — max 10 MB each, up to 10 images</p>
        </div>
        <input type="file" id="image-file-input" multiple
               accept="image/jpeg,image/png,image/webp,image/gif" class="hidden">

        <div id="image-upload-error" class="text-xs text-red-600 mt-2 hidden"></div>
        @error('images')
          <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
        @enderror
        @error('uploaded_images')
          <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
        @enderror
      </div>

      {{-- Publish Status --}}
      <div class="md:col-span-2">
        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Publish Status</label>
        <select name="status"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
          <option value="draft"
            @selected(old('status', $product->status ?? 'draft') === 'draft')>
            Draft — not visible to customers
          </option>
          <option value="published"
            @selected(old('status', $product->status ?? '') === 'published')>
            Published — live on storefront
          </option>
        </select>
        @error('status')
          <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Toggles --}}
      <div class="md:col-span-2 flex items-center gap-6">
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="is_featured" value="1"
                 @checked(old('is_featured', $product->is_featured ?? false))
                 class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
          <span class="text-sm font-semibold text-gray-700">Mark as Featured</span>
        </label>
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="is_active" value="1"
                 @checked(old('is_active', $product->is_active ?? true))
                 class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
          <span class="text-sm font-semibold text-gray-700">Active <span class="text-gray-400 font-normal">(visible to customers)</span></span>
        </label>
      </div>

      {{-- SEO / Meta --}}
      <div class="md:col-span-2 border-t border-gray-100 pt-6 mt-2">
        <div class="flex items-center gap-2 mb-4">
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <div>
            <p class="text-xs font-black text-gray-500 uppercase tracking-widest">SEO / Meta</p>
            <p class="text-xs text-gray-400 mt-0.5">Leave blank to auto-generate from product name and description.</p>
          </div>
        </div>
        <div class="border border-gray-200 rounded-lg p-5 bg-gray-50 grid grid-cols-1 gap-4">

          {{-- URL Slug --}}
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
              URL Slug <span class="font-normal normal-case text-gray-400">(auto-generated from name)</span>
            </label>
            <div class="flex items-center gap-2">
              <span class="text-xs text-gray-400 font-mono shrink-0">/product/</span>
              <input type="text" name="slug"
                     value="{{ old('slug', $product->slug ?? '') }}"
                     placeholder="e.g. extension-cord-4-gang"
                     class="flex-1 px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm font-mono focus:ring-1 focus:ring-brand-500 outline-none">
            </div>
            <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers, and hyphens only. Leave blank to auto-generate.</p>
            @error('slug')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- SEO Title --}}
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
              SEO Title <span class="font-normal normal-case text-gray-400">(max 70 characters)</span>
            </label>
            <input type="text" name="meta_title" maxlength="70"
                   value="{{ old('meta_title', $product->meta_title ?? '') }}"
                   placeholder="{{ ($product->name ?? 'Product Name') . ' | DealMindanao' }}"
                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-500 outline-none"
                   id="meta-title-input">
            <p class="text-xs text-gray-400 mt-1">
              <span id="meta-title-count">0</span>/70 characters.
              Shown as the browser tab title and Google headline.
            </p>
            @error('meta_title')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- SEO Description --}}
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
              SEO Description <span class="font-normal normal-case text-gray-400">(max 160 characters)</span>
            </label>
            <textarea name="meta_description" maxlength="320" rows="3"
                      placeholder="Buy [Product Name] from verified Mindanao sellers on DealMindanao."
                      class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-500 outline-none resize-none"
                      id="meta-desc-input">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
            <p class="text-xs text-gray-400 mt-1">
              <span id="meta-desc-count">0</span>/320 characters.
              Shown under the page title in Google results.
            </p>
            @error('meta_description')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- SEO Keywords --}}
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">
              SEO Keywords <span class="font-normal normal-case text-gray-400">(optional, comma-separated)</span>
            </label>
            <input type="text" name="meta_keywords" maxlength="500"
                   value="{{ old('meta_keywords', $product->meta_keywords ?? '') }}"
                   placeholder="e.g. extension cord, 4 gang, hardware, Mindanao"
                   class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-500 outline-none">
            <p class="text-xs text-gray-400 mt-1">Comma-separated keywords. Auto-generated from product name and category if left blank.</p>
            @error('meta_keywords')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

        </div>
      </div>

    </div>{{-- /grid --}}

    <div class="mt-8 flex gap-3">
      <button type="submit" class="btn-primary px-8 py-3 rounded-lg font-bold">
        {{ $isEdit ? 'Update Product' : 'Save Product' }}
      </button>
      <a href="{{ route('admin.products.index') }}"
         class="px-8 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
        Cancel
      </a>
    </div>
  </form>
</div>

@push('scripts')
<script>
(function () {
    // ── SRP auto-compute ────────────────────────────────────────────────────
    const MARGIN        = {{ config('products.default_margin', 0.25) }};
    const supplierInput = document.getElementById('supplier-price');
    const srpInput      = document.getElementById('srp-price');
    const srpBadge      = document.getElementById('srp-auto-badge');
    // In edit mode the SRP field is already filled — treat it as manually entered.
    let srpManual       = Boolean(srpInput && srpInput.value.trim());

    function autoFillSrp() {
        if (!supplierInput || !srpInput || srpManual) return;
        const cost = parseFloat(supplierInput.value);
        if (!isNaN(cost) && cost > 0) {
            srpInput.value = (Math.round(cost * (1 + MARGIN) * 100) / 100).toFixed(2);
            if (srpBadge) srpBadge.classList.remove('hidden');
        } else {
            srpInput.value = '';
            if (srpBadge) srpBadge.classList.add('hidden');
        }
    }

    if (supplierInput) supplierInput.addEventListener('input', autoFillSrp);
    if (srpInput) {
        srpInput.addEventListener('input', function () {
            srpManual = Boolean(this.value.trim());
            if (srpBadge) srpBadge.classList.toggle('hidden', srpManual);
        });
        srpInput.addEventListener('blur', function () {
            if (!this.value.trim()) { srpManual = false; autoFillSrp(); }
        });
    }

    // ── Promo status chip ───────────────────────────────────────────────────
    const discountInput = document.getElementById('promo-discount');
    const startsInput   = document.getElementById('promo-starts');
    const endsInput     = document.getElementById('promo-ends');
    const chip          = document.getElementById('promo-status-chip');

    const CHIP = {
        none:      'bg-gray-200 text-gray-500',
        active:    'bg-green-100 text-green-700',
        scheduled: 'bg-blue-100 text-blue-700',
        expired:   'bg-red-100 text-red-500',
    };

    function updateStatus() {
        if (!discountInput || !chip) return;
        const discount = parseFloat(discountInput.value);
        if (!discount || discount <= 0) {
            chip.className = `text-xs font-semibold px-2.5 py-1 rounded-full ${CHIP.none}`;
            chip.textContent = '\u2014 No Promo';
            return;
        }
        const now    = new Date();
        const starts = startsInput && startsInput.value ? new Date(startsInput.value) : null;
        const ends   = endsInput   && endsInput.value   ? new Date(endsInput.value)   : null;
        if (ends && now > ends) {
            chip.className = `text-xs font-semibold px-2.5 py-1 rounded-full ${CHIP.expired}`;
            chip.textContent = '\u2715 Expired';
        } else if (starts && now < starts) {
            chip.className = `text-xs font-semibold px-2.5 py-1 rounded-full ${CHIP.scheduled}`;
            chip.textContent = '\u23F3 Scheduled';
        } else {
            chip.className = `text-xs font-semibold px-2.5 py-1 rounded-full ${CHIP.active}`;
            chip.textContent = '\u25CF Active';
        }
    }

    if (discountInput) {
        discountInput.addEventListener('input', updateStatus);
        if (startsInput) startsInput.addEventListener('change', updateStatus);
        if (endsInput)   endsInput.addEventListener('change', updateStatus);
        updateStatus(); // initialise chip from existing values on page load
    }
})();
</script>

<script>
// ── Image uploader ──────────────────────────────────────────────────────────
(function () {
    const dropzone    = document.getElementById('image-dropzone');
    const fileInput   = document.getElementById('image-file-input');
    const hiddenInput = document.getElementById('uploaded-images-input');
    const previewGrid = document.getElementById('image-preview-grid');
    const errorBox    = document.getElementById('image-upload-error');
    const uploadUrl   = '{{ route("admin.products.uploadImage") }}';
    const csrfToken   = '{{ csrf_token() }}';

    // Seed from existing images (edit mode pre-populates the hidden field)
    let uploadedPaths = hiddenInput.value
        ? hiddenInput.value.split(',').filter(Boolean)
        : [];

    function syncHidden() {
        hiddenInput.value = uploadedPaths.join(',');
    }

    function showError(msg) {
        errorBox.textContent = msg;
        errorBox.classList.remove('hidden');
        setTimeout(() => errorBox.classList.add('hidden'), 5000);
    }

    // Wire up remove buttons on server-rendered existing previews (edit mode)
    previewGrid.querySelectorAll('.remove-img').forEach(btn => {
        btn.addEventListener('click', function () {
            uploadedPaths = uploadedPaths.filter(p => p !== this.dataset.path);
            syncHidden();
            this.closest('.relative').remove();
        });
    });

    function addPreview(path) {
        const wrap = document.createElement('div');
        wrap.className = 'relative group';
        wrap.innerHTML = `
            <img src="${path}" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
            <button type="button" data-path="${path}"
                class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 hover:bg-red-600 text-white rounded-full text-xs flex items-center justify-center leading-none remove-img">\u2715</button>`;
        wrap.querySelector('.remove-img').addEventListener('click', function () {
            uploadedPaths = uploadedPaths.filter(p => p !== this.dataset.path);
            syncHidden();
            wrap.remove();
        });
        previewGrid.appendChild(wrap);
    }

    async function uploadFile(file) {
        const fd = new FormData();
        fd.append('image', file);
        fd.append('_token', csrfToken);
        const res  = await fetch(uploadUrl, { method: 'POST', body: fd });
        const json = await res.json();
        if (!res.ok || !json.success) throw new Error(json.message || 'Upload failed');
        return json.path;
    }

    async function handleFiles(files) {
        const remaining = 10 - uploadedPaths.length;
        const toUpload  = Array.from(files).slice(0, remaining);
        for (const file of toUpload) {
            try {
                const path = await uploadFile(file);
                uploadedPaths.push(path);
                syncHidden();
                addPreview(path);
            } catch (e) {
                showError(e.message);
            }
        }
    }

    fileInput.addEventListener('change', e => handleFiles(e.target.files));

    dropzone.addEventListener('dragover', e => {
        e.preventDefault();
        dropzone.classList.add('border-brand-500', 'bg-brand-50');
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-brand-500', 'bg-brand-50');
    });
    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('border-brand-500', 'bg-brand-50');
        handleFiles(e.dataTransfer.files);
    });
})();
</script>

<script>
// ── Technical Specifications Builder ────────────────────────────────────────
(function () {
    const builder    = document.getElementById('specs-builder');
    const hiddenInput = document.getElementById('specs-json-input');
    const addGroupBtn = document.getElementById('add-spec-group');
    if (!builder) return;

    // Pre-existing data in edit mode
    const existing = @json($product->specifications ?? []);

    function serialize() {
        const result = [];
        builder.querySelectorAll('.spec-group').forEach(groupEl => {
            const groupName = groupEl.querySelector('.group-name').value.trim();
            if (!groupName) return;
            const items = [];
            groupEl.querySelectorAll('.spec-row').forEach(row => {
                const label = row.querySelector('.spec-label').value.trim();
                const value = row.querySelector('.spec-value').value.trim();
                if (label) items.push({ label, value });
            });
            if (items.length) result.push({ group: groupName, items });
        });
        hiddenInput.value = JSON.stringify(result);
    }

    function addRow(container, label, value) {
        const row = document.createElement('div');
        row.className = 'spec-row flex gap-2 items-center';
        row.innerHTML = `
            <input type="text" class="spec-label flex-1 min-w-0 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-500 outline-none" placeholder="Label (e.g. Color)">
            <input type="text" class="spec-value flex-1 min-w-0 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-500 outline-none" placeholder="Value (e.g. White)">
            <button type="button" class="remove-row w-7 h-7 flex-shrink-0 flex items-center justify-center text-gray-400 hover:text-red-500 rounded transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>`;
        row.querySelector('.spec-label').value = label || '';
        row.querySelector('.spec-value').value = value || '';
        row.querySelector('.remove-row').addEventListener('click', () => { row.remove(); serialize(); });
        row.querySelectorAll('input').forEach(i => i.addEventListener('input', serialize));
        container.appendChild(row);
    }

    function addGroup(name, items) {
        const group = document.createElement('div');
        group.className = 'spec-group border border-gray-200 rounded-lg overflow-hidden';
        group.innerHTML = `
            <div class="flex items-center gap-2 p-3 bg-gray-50 border-b border-gray-200">
                <input type="text" class="group-name flex-1 px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm font-semibold focus:ring-1 focus:ring-brand-500 outline-none" placeholder="Group name (e.g. Housing)">
                <button type="button" class="remove-group flex-shrink-0 text-xs text-red-500 hover:text-red-700 font-semibold px-2 py-1 rounded transition-colors">Remove</button>
            </div>
            <div class="spec-rows p-3 space-y-2"></div>
            <div class="px-3 pb-3">
                <button type="button" class="add-row-btn text-xs text-brand-600 hover:text-brand-700 font-semibold flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Row
                </button>
            </div>`;
        const rowsContainer = group.querySelector('.spec-rows');
        group.querySelector('.group-name').value = name || '';
        group.querySelector('.group-name').addEventListener('input', serialize);
        group.querySelector('.remove-group').addEventListener('click', () => { group.remove(); serialize(); });
        group.querySelector('.add-row-btn').addEventListener('click', () => { addRow(rowsContainer, '', ''); });
        (items && items.length ? items : [{ label: '', value: '' }])
            .forEach(item => addRow(rowsContainer, item.label, item.value));
        builder.appendChild(group);
        serialize();
    }

    // Initialise from existing data
    if (Array.isArray(existing) && existing.length) {
        existing.forEach(g => addGroup(g.group || '', g.items || []));
    }

    addGroupBtn.addEventListener('click', () => addGroup('', []));

    // Serialize right before the form submits
    addGroupBtn.closest('form').addEventListener('submit', serialize);
})();
</script>

<script>
// ── Variants Builder ────────────────────────────────────────────────
(function () {
    const MARGIN         = {{ config('products.default_margin', 0.30) }};
    const rowsContainer  = document.getElementById('variant-rows');
    const hiddenInput    = document.getElementById('variants-json-input');
    const addBtn         = document.getElementById('add-variant-row');
    const attrInput      = document.getElementById('variant-attribute');
    if (!rowsContainer) return;

    const existing = @json($product->variants ?? null);

    function serialize() {
        const attribute = attrInput.value.trim();
        const options   = [];
        rowsContainer.querySelectorAll('.variant-row').forEach(row => {
            const label = row.querySelector('.v-label').value.trim();
            const cost  = parseFloat(row.querySelector('.v-cost').value);
            const price = parseFloat(row.querySelector('.v-srp').value);
            const stock = parseInt(row.querySelector('.v-stock').value, 10);
            if (label) options.push({
                label,
                cost:  isNaN(cost)  ? 0 : Math.round(cost * 100) / 100,
                price: isNaN(price) ? 0 : Math.round(price * 100) / 100,
                stock: isNaN(stock) ? 0 : stock
            });
        });
        hiddenInput.value = (attribute && options.length)
            ? JSON.stringify({ attribute, options })
            : '';
    }

    function addRow(label, cost, price, stock) {
        const row = document.createElement('div');
        row.className = 'variant-row grid grid-cols-12 gap-2 items-center';
        row.innerHTML = `
            <input type="text"   class="v-label col-span-4 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-500 outline-none" placeholder="e.g. 3M">
            <input type="number" class="v-cost  col-span-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-500 outline-none" placeholder="0.00" step="0.01" min="0">
            <div class="col-span-3 relative">
                <input type="number" class="v-srp w-full px-3 py-2 bg-white border border-brand-200 rounded-lg text-sm font-semibold text-brand-700 focus:ring-1 focus:ring-brand-500 outline-none" placeholder="auto" step="0.01" min="0">
                <span class="v-srp-badge absolute right-2 top-1/2 -translate-y-1/2 text-[9px] text-brand-500 font-bold pointer-events-none hidden">auto</span>
            </div>
            <input type="number" class="v-stock col-span-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-brand-500 outline-none" placeholder="0" min="0">
            <div class="col-span-1 flex justify-center">
                <button type="button" class="remove-variant w-7 h-7 flex items-center justify-center text-gray-400 hover:text-red-500 rounded transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>`;

        const costInput = row.querySelector('.v-cost');
        const srpInput  = row.querySelector('.v-srp');
        const srpBadge  = row.querySelector('.v-srp-badge');
        let srpManual   = false;

        // Pre-populate from existing saved data
        row.querySelector('.v-label').value = label || '';
        const parsedCost  = parseFloat(cost);
        const parsedPrice = parseFloat(price);
        if (cost !== undefined && cost !== '' && !isNaN(parsedCost)) {
            costInput.value = parsedCost.toFixed(2);
        }
        if (price !== undefined && price !== '') {
            srpInput.value = parsedPrice.toFixed(2);
            // Only treat as manually-set if price differs from auto-computed value
            const autoPrice = Math.round(parsedCost * (1 + MARGIN) * 100) / 100;
            srpManual = Math.abs(parsedPrice - autoPrice) > 0.005;
            if (!srpManual) srpBadge.classList.remove('hidden');
        }
        row.querySelector('.v-stock').value = stock !== undefined && stock !== '' ? stock : '';

        costInput.addEventListener('input', function () {
            if (!srpManual) {
                const cost = parseFloat(this.value);
                if (!isNaN(cost) && cost > 0) {
                    srpInput.value = (Math.round(cost * (1 + MARGIN) * 100) / 100).toFixed(2);
                    srpBadge.classList.remove('hidden');
                } else {
                    srpInput.value = '';
                    srpBadge.classList.add('hidden');
                }
            }
            serialize();
        });

        srpInput.addEventListener('input', function () {
            srpManual = !!this.value.trim();
            srpBadge.classList.toggle('hidden', srpManual);
            serialize();
        });

        srpInput.addEventListener('blur', function () {
            if (!this.value.trim()) {
                srpManual = false;
                costInput.dispatchEvent(new Event('input'));
            }
        });

        row.querySelector('.v-label').addEventListener('input', serialize);
        row.querySelector('.v-stock').addEventListener('input', serialize);
        row.querySelector('.remove-variant').addEventListener('click', () => { row.remove(); serialize(); });
        rowsContainer.appendChild(row);
        serialize();
    }

    // Load existing
    if (existing && existing.attribute && Array.isArray(existing.options) && existing.options.length) {
        existing.options.forEach(o => addRow(o.label, o.cost, o.price, o.stock));
    }

    attrInput.addEventListener('input', serialize);
    addBtn.addEventListener('click', () => addRow('', '', ''));
    addBtn.closest('form').addEventListener('submit', serialize);
})();
</script>

<script>
// ── SEO character counters ───────────────────────────────────────────────────
(function () {
    function bindCounter(inputId, counterId) {
        const el = document.getElementById(inputId);
        const counter = document.getElementById(counterId);
        if (!el || !counter) return;
        function update() { counter.textContent = el.value.length; }
        el.addEventListener('input', update);
        update();
    }
    bindCounter('meta-title-input', 'meta-title-count');
    bindCounter('meta-desc-input',  'meta-desc-count');
})();
</script>

@endpush
