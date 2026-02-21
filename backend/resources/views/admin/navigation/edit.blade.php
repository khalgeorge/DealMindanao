@extends('layouts.admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900">Edit Menu Item</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Update navigation details</p>
    </div>
    <a href="{{ route('admin.navigation.index') }}" class="btn-secondary">Back</a>
</header>

<div class="admin-content">
    @if($errors->any())
        <div class="alert-error mb-6">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.navigation.update', $item) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-8 max-w-2xl">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Label</label>
                    <input name="label" class="input" value="{{ old('label', $item->label) }}" required>
                    @error('label')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">URL</label>
                    <input name="url" class="input" value="{{ old('url', $item->url) }}" required>
                    <p class="text-xs text-gray-400 mt-1">Use absolute or relative URLs.</p>
                    @error('url')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Location</label>
                    <select name="location" class="input" required>
                        <option value="header" @selected(old('location', $item->location) === 'header')>Header</option>
                        <option value="footer" @selected(old('location', $item->location) === 'footer')>Footer</option>
                    </select>
                    @error('location')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Position</label>
                    <input name="position" type="number" min="0" class="input" value="{{ old('position', $item->position) }}">
                    <p class="text-xs text-gray-400 mt-1">Lower numbers appear first.</p>
                    @error('position')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active)) class="w-4 h-4 rounded text-brand-600">
                    <span class="text-sm font-bold text-gray-700">Active</span>
                </label>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex gap-3">
                <button class="btn-primary" type="submit">Save Changes</button>
                <a href="{{ route('admin.navigation.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
