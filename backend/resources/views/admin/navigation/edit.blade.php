@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Edit Menu Item</h1>
        <p class="text-sm text-gray-500">Update navigation details.</p>
    </div>
    <a href="/admin/navigation" class="btn-secondary">Back</a>
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

    <form action="/admin/navigation/{{ $item->id }}" method="POST" class="card max-w-3xl">
        @csrf
        @method('PUT')

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm">Label</label>
            <input name="label" class="input" value="{{ old('label', $item->label) }}" required>
            @error('label')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">URL</label>
            <input name="url" class="input" value="{{ old('url', $item->url) }}" required>
            @error('url')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">Location</label>
            <input name="location" class="input" value="{{ old('location', $item->location) }}" required>
            @error('location')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">Position</label>
            <input name="position" type="number" min="0" class="input" value="{{ old('position', $item->position) }}">
            @error('position')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-4">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active))>
            Active
        </label>
    </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-primary" type="submit">Save Changes</button>
            <a href="/admin/navigation" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
