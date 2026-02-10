@extends('layouts.admin')

@section('content')
@php
    $isEdit = isset($product) && $product;
@endphp

<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h1>{{ $isEdit ? 'Edit Product' : 'Add Product' }}</h1>
        <p>{{ $isEdit ? 'Update pricing, images, and availability.' : 'Create a new listing for the marketplace.' }}</p>
    </div>
    <a href="/admin/products" class="btn-secondary">Back to Products</a>
</div>

<form action="{{ $isEdit ? '/admin/products/' . $product->id : '/admin/products' }}" method="POST" enctype="multipart/form-data" class="card max-w-3xl">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm">Product Name</label>
            <input name="name" class="input" value="{{ old('name', $product->name ?? '') }}" required>
            @if(!app()->environment('production'))
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            @endif
        </div>

        <div>
            <label class="text-sm">Meta Title</label>
            <input name="meta_title" class="input" value="{{ old('meta_title', $product->meta_title ?? '') }}">
            <p class="text-xs text-gray-500 mt-1">Optional. Falls back to the product name.</p>
            @if(!app()->environment('production'))
                @error('meta_title')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            @endif
        </div>

        <div>
            <label class="text-sm">Price</label>
            <input name="price" type="number" step="0.01" class="input" value="{{ old('price', $product->price ?? '') }}" required>
            @if(!app()->environment('production'))
                @error('price')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            @endif
        </div>

        <div>
            <label class="text-sm">Discount</label>
            <input name="discount" type="number" step="0.01" class="input" value="{{ old('discount', $product->discount ?? '') }}">
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
                    <option value="{{ $company->id }}" @selected(old('company_id', $product->company_id ?? '') == $company->id)>
                        {{ $company->name }}
                    </option>
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
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                        {{ $category->name }}
                    </option>
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
        <textarea name="description" class="textarea" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
        @if(!app()->environment('production'))
            @error('description')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        @endif
    </div>

    <div class="mt-4">
        <label class="text-sm">Meta Description</label>
        <textarea name="meta_description" class="textarea" rows="3">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
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
        @if($isEdit && $product->images)
            <div class="mt-3 flex flex-wrap gap-3">
                @foreach($product->images as $path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($path) }}" alt="{{ $product->name }} image" class="h-16 w-16 rounded-lg object-cover border border-gray-100">
                @endforeach
            </div>
        @endif
    </div>

    <div class="mt-4 flex gap-4">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false))>
            Featured
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
            Active
        </label>
    </div>

    <div class="mt-6 flex gap-3">
        <button class="btn-primary">{{ $isEdit ? 'Update Product' : 'Save Product' }}</button>
        <a href="/admin/products" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
