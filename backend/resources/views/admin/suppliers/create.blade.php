@extends('layouts.admin')

@section('content')
<header class="admin-header">
  <div>
    <h1 class="text-xl font-black text-gray-900">Add Partner</h1>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Register New Seller</p>
  </div>
  <a href="{{ route('admin.suppliers.index') }}" class="btn-secondary flex items-center gap-2 px-5">
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

  <form method="POST" action="{{ route('admin.suppliers.store') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    @csrf

    <div class="p-8 space-y-6">

      {{-- Name --}}
      <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
          Partner Name <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" value="{{ old('name') }}" required
          placeholder="e.g. GEEANN Hardware Trading"
          class="input w-full @error('name') border-red-300 bg-red-50 @enderror">
        @error('name')
        <p class="text-[11px] text-red-500 font-bold mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Contact Person --}}
      <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Contact Person</label>
        <input type="text" name="contact_person" value="{{ old('contact_person') }}"
          placeholder="e.g. Juan dela Cruz"
          class="input w-full @error('contact_person') border-red-300 bg-red-50 @enderror">
        @error('contact_person')
        <p class="text-[11px] text-red-500 font-bold mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Contact Email + Phone --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Contact Email</label>
          <input type="email" name="contact_email" value="{{ old('contact_email') }}"
            placeholder="supplier@example.com"
            class="input w-full @error('contact_email') border-red-300 bg-red-50 @enderror">
          @error('contact_email')
          <p class="text-[11px] text-red-500 font-bold mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Contact Phone</label>
          <input type="tel" name="contact_phone" value="{{ old('contact_phone') }}"
            placeholder="+63 912 345 6789"
            class="input w-full @error('contact_phone') border-red-300 bg-red-50 @enderror">
          @error('contact_phone')
          <p class="text-[11px] text-red-500 font-bold mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      {{-- Region --}}
      <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Region / Location <span class="text-gray-400 font-normal normal-case tracking-normal">(shown on product cards)</span></label>
        <input type="text" name="region" value="{{ old('region') }}"
          placeholder="e.g. Davao City, Davao del Sur"
          class="input w-full @error('region') border-red-300 bg-red-50 @enderror">
        @error('region')
        <p class="text-[11px] text-red-500 font-bold mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Internal Notes --}}
      <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Internal Notes</label>
        <textarea name="internal_notes" rows="3"
          placeholder="Terms, lead times, minimum orders, etc."
          class="input w-full resize-none @error('internal_notes') border-red-300 bg-red-50 @enderror">{{ old('internal_notes') }}</textarea>
        @error('internal_notes')
        <p class="text-[11px] text-red-500 font-bold mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Active Status --}}
      <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-100">
        <input type="checkbox" id="is_active" name="is_active" value="1"
          {{ old('is_active', true) ? 'checked' : '' }}
          class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
        <label for="is_active" class="text-xs font-black text-gray-700 uppercase tracking-wide cursor-pointer">
          Active
          <span class="block text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Partner can be assigned to products</span>
        </label>
      </div>

      {{-- Verified Status --}}
      <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg border border-blue-100">
        <input type="checkbox" id="is_verified" name="is_verified" value="1"
          {{ old('is_verified') ? 'checked' : '' }}
          class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        <label for="is_verified" class="text-xs font-black text-blue-700 uppercase tracking-wide cursor-pointer">
          Verified Partner
          <span class="block text-[9px] text-blue-400 font-bold uppercase tracking-widest mt-0.5">Identity and business has been verified</span>
        </label>
      </div>

    </div>

    <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex gap-3">
      <a href="{{ route('admin.suppliers.index') }}" class="flex-1 py-4 text-center bg-white border border-gray-200 text-gray-600 rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">
        Cancel
      </a>
      <button type="submit" class="flex-[2] py-4 bg-brand-600 text-white rounded-lg font-black text-[10px] uppercase tracking-widest shadow-lg shadow-brand-100 hover:bg-brand-700 transition-all">
        Register Partner
      </button>
    </div>

  </form>

</div>
@endsection
