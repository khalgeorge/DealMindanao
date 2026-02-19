@extends('layouts.admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Edit Page</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $label }}</p>
    </div>
    <a href="{{ route('admin.settings.pages.index') }}" class="btn-secondary btn-sm">← Back</a>
</header>

@if($errors->any())
    <div class="alert-error mb-4">
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.settings.pages.update', $page->slug) }}" method="POST" enctype="multipart/form-data" class="card max-w-4xl">
    @csrf
    @method('PUT')

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm">Title</label>
            <input name="title" class="input" value="{{ old('title', $page->title) }}" required>
            @error('title')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">Meta Title</label>
            <input name="meta_title" class="input" value="{{ old('meta_title', $page->meta_title) }}">
            <p class="text-xs text-gray-500 mt-1">Optional. Falls back to the page title.</p>
            @error('meta_title')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">Subtitle</label>
            <input name="subtitle" class="input" value="{{ old('subtitle', $page->subtitle) }}">
            @error('subtitle')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">Meta Description</label>
            <input name="meta_description" class="input" value="{{ old('meta_description', $page->meta_description) }}">
            <p class="text-xs text-gray-500 mt-1">Optional. Used for search and sharing.</p>
            @error('meta_description')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="md:col-span-2">
            <label class="text-sm">Body</label>
            <textarea name="body" class="textarea" rows="8">{{ old('body', $page->body) }}</textarea>
            @error('body')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">Logo</label>
            <input type="file" name="logo" class="input">
            @error('logo')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
            @if($page->logo_path)
                <div class="mt-3 flex items-center gap-3">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($page->logo_path) }}" alt="{{ $page->title }} logo" class="h-16 w-16 rounded-lg object-cover border border-gray-100">
                    <p class="text-xs text-gray-500">Current logo</p>
                </div>
            @endif
        </div>
        <div>
            <label class="text-sm">Hero Image</label>
            <input type="file" name="hero_image" class="input">
            @error('hero_image')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
            @if($page->hero_image_path)
                <div class="mt-3 flex items-center gap-3">
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($page->hero_image_path) }}" alt="{{ $page->title }} hero" class="h-16 w-16 rounded-lg object-cover border border-gray-100">
                    <p class="text-xs text-gray-500">Current hero image</p>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <button class="btn-primary" type="submit">Save Changes</button>
        <a href="{{ route('admin.settings.pages.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
@endsection
