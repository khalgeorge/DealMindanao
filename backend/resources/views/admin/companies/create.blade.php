@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Add Company</h1>
        <p class="text-sm text-gray-500">Create a new partner profile.</p>
    </div>
    <a href="/admin/companies" class="btn-secondary">Back</a>
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

    <form action="/admin/companies" method="POST" enctype="multipart/form-data" class="card max-w-3xl">
        @csrf

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="text-sm">Company Name</label>
            <input name="name" class="input" value="{{ old('name') }}" required>
            @error('name')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">City</label>
            <input name="city" class="input" value="{{ old('city') }}">
            @error('city')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">Contact Email</label>
            <input name="contact_email" type="email" class="input" value="{{ old('contact_email') }}">
            @error('contact_email')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="text-sm">Contact Phone</label>
            <input name="contact_phone" class="input" value="{{ old('contact_phone') }}">
            @error('contact_phone')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="md:col-span-2">
            <label class="text-sm">Messenger Link</label>
            <input name="messenger_link" class="input" value="{{ old('messenger_link') }}">
            @error('messenger_link')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="md:col-span-2">
            <label class="text-sm">Logo</label>
            <input type="file" name="logo" class="input">
            @error('logo')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-primary" type="submit">Save Company</button>
            <a href="/admin/companies" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
