@extends('layouts.admin')

@section('content')
<header class="admin-header">
  <div>
    <h1 class="text-xl font-black text-gray-900">Add Brand</h1>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Register Product Brand</p>
  </div>
  <a href="{{ route('admin.brands.index') }}" class="btn-secondary flex items-center gap-2 px-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    Back
  </a>
</header>

<div class="admin-content max-w-2xl">

  @if($errors->any())
  <div class="mb-6 px-5 py-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm font-bold">
    <p class="font-black mb-2">Please fix the following errors:</p>
    <ul class="list-disc list-inside space-y-1 font-medium">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form method="POST" action="{{ route('admin.brands.store') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    @csrf

    <div class="p-8 space-y-6">

      {{-- Name --}}
      <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
          Brand Name <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" value="{{ old('name') }}" required
          placeholder="e.g. Mindanao Farms"
          class="input w-full @error('name') border-red-300 bg-red-50 @enderror">
        @error('name')
        <p class="text-[11px] text-red-500 font-bold mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Description --}}
      <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Description <span class="text-gray-300 font-medium normal-case">(optional)</span></label>
        <textarea name="description" rows="3"
          placeholder="Brief description of the brand..."
          class="input w-full resize-none @error('description') border-red-300 bg-red-50 @enderror">{{ old('description') }}</textarea>
        @error('description')
        <p class="text-[11px] text-red-500 font-bold mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Website --}}
      <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Website <span class="text-gray-300 font-medium normal-case">(optional)</span></label>
        <input type="url" name="website" value="{{ old('website') }}"
          placeholder="https://example.com"
          class="input w-full @error('website') border-red-300 bg-red-50 @enderror">
        @error('website')
        <p class="text-[11px] text-red-500 font-bold mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Active Status --}}
      <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-100">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
          {{ old('is_active', '1') == '1' ? 'checked' : '' }}
          class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
        <label for="is_active" class="text-xs font-black text-gray-700 uppercase tracking-wide cursor-pointer">
          Active
          <span class="block text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Brand is available for product assignment</span>
        </label>
      </div>

    </div>

    <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex gap-3">
      <a href="{{ route('admin.brands.index') }}" class="flex-1 py-3.5 bg-white border border-gray-200 text-gray-600 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all text-center">
        Cancel
      </a>
      <button type="submit" class="flex-[2] py-3.5 bg-brand-600 text-white rounded-lg font-black text-[10px] uppercase tracking-widest shadow-lg shadow-brand-100 hover:bg-brand-700 transition-all">
        Create Brand
      </button>
    </div>
  </form>

</div>
@endsection
