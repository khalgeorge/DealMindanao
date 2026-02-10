@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Edit Category</h1>
        <p class="text-sm text-gray-500">Update category details.</p>
    </div>
    <a href="/admin/categories" class="btn-secondary">Back</a>
</div>

<div class="space-y-6">
    @if($errors->any())
        <div class="alert-error">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/admin/categories/{{ $category->id }}" method="POST" class="card max-w-2xl">
        @csrf
        @method('PUT')

    <div class="grid gap-4">
        <div>
            <label class="text-sm">Category Name</label>
            <input name="name" class="input" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">Slug</label>
            <input name="slug" class="input" value="{{ old('slug', $category->slug) }}" placeholder="Auto-generated if left blank">
            <p class="text-xs text-gray-500 mt-1">Optional. Leave blank to generate from the name.</p>
            @error('slug')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-primary" type="submit">Update Category</button>
            <a href="/admin/categories" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
