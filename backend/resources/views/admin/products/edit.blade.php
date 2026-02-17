@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<header class="admin-header">
  <div>
    <h1 class="text-xl font-black text-gray-900">Edit Product</h1>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Update product information</p>
  </div>
  <div class="flex items-center gap-4">
    <a href="/admin/products" class="btn-secondary flex items-center gap-2">
       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
       Back to Products
    </a>
  </div>
</header>

<div class="admin-content">
  <div class="max-w-4xl mx-auto">
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
      <form action="/admin/products/{{ $product->id }}" method="POST" enctype="multipart/form-data" class="p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Product Name</label>
            <input type="text" name="name" required value="{{ old('name', $product->name) }}" placeholder="e.g. Premium Davao Durian" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
            @error('name')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>
          
          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Category</label>
            <select name="category_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
              <option value="">Select Category</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
              @endforeach
            </select>
            @error('category_id')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Partner / Vendor</label>
            <select name="company_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
              <option value="">Select Partner</option>
              @foreach($companies as $company)
                <option value="{{ $company->id }}" @selected(old('company_id', $product->company_id) == $company->id)>{{ $company->name }}</option>
              @endforeach
            </select>
            @error('company_id')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Price (₱)</label>
            <input type="number" name="price" required step="0.01" value="{{ old('price', $product->price) }}" placeholder="250.00" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
            @error('price')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Stock Level</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" placeholder="100" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
            @error('stock')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="md:col-span-2">
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Description</label>
            <textarea name="description" rows="4" placeholder="Detailed product description..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">{{ old('description', $product->description) }}</textarea>
            @error('description')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="md:col-span-2">
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Current Images</label>
            @if($product->images && count($product->images) > 0)
              <div class="flex flex-wrap gap-4 mb-4">
                @foreach($product->images as $image)
                  <div class="relative">
                    <img src="{{ $image }}" alt="Product image" class="w-24 h-24 rounded-lg object-cover border border-gray-200">
                  </div>
                @endforeach
              </div>
            @else
              <p class="text-sm text-gray-500 mb-4">No images uploaded</p>
            @endif
            
            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Upload New Images</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all outline-none text-gray-900">
            <p class="text-xs text-gray-500 mt-1">Upload new images to replace existing ones (optional)</p>
            @error('images')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="md:col-span-2">
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active)) class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
              <span class="text-sm font-semibold text-gray-700">Active (visible to customers)</span>
            </label>
          </div>
        </div>

        <div class="mt-8 flex gap-3">
          <button type="submit" class="btn-primary px-8 py-3 rounded-lg font-bold">Update Product</button>
          <a href="/admin/products" class="px-8 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
