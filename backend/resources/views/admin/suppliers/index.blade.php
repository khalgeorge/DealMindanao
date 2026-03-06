@extends('layouts.admin')

@section('content')
<header class="admin-header">
  <div>
    <h1 class="text-xl font-black text-gray-900">Partners</h1>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Sellers on DealMindanao</p>
  </div>
  <a href="{{ route('admin.suppliers.create') }}" class="btn-primary flex items-center gap-2 px-6">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
    Add Partner
  </a>
</header>

<div class="admin-content">

  {{-- Flash Messages --}}
  @if(session('success'))
  <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-green-50 border border-green-200 rounded-lg text-green-800 text-sm font-bold">
    <svg class="w-4 h-4 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    {{ session('success') }}
  </div>
  @endif

  @if(session('error'))
  <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm font-bold">
    <svg class="w-4 h-4 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    {{ session('error') }}
  </div>
  @endif

  {{-- Search / Filter --}}
  <form method="GET" action="{{ route('admin.suppliers.index') }}" class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
    <div class="flex items-center gap-4 w-full md:w-auto flex-1">
      <div class="relative flex-1 md:max-w-96">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </span>
        <input type="text" name="search" value="{{ $search }}" placeholder="Search partners…" class="input pl-11 py-3">
      </div>

      <select name="status" onchange="this.form.submit()" class="input py-3 pr-8 min-w-[140px]">
        <option value="">All Status</option>
        <option value="active"   {{ $status === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
      </select>

      <a href="{{ route('admin.suppliers.index') }}" class="btn-secondary px-4 py-3 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 whitespace-nowrap">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        Reset
      </a>
    </div>
  </form>

  {{-- Table --}}
  <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 border-b border-gray-100">
        <tr>
          <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Partner</th>
          <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest hidden md:table-cell">Contact</th>
          <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Products</th>
          <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Verified</th>
          <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
          <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($suppliers as $supplier)
        <tr class="hover:bg-gray-50/50 transition-colors group">
          <td class="px-6 py-4">
            <div class="font-black text-gray-900">{{ $supplier->name }}</div>
            @if($supplier->contact_person)
            <div class="text-[11px] text-gray-400 font-bold mt-0.5">{{ $supplier->contact_person }}</div>
            @endif
            @if($supplier->address)
            <div class="text-[11px] text-gray-400 font-medium mt-0.5 truncate max-w-xs">{{ $supplier->address }}</div>
            @endif
          </td>

          <td class="px-6 py-4 hidden md:table-cell">
            @if($supplier->contact_email)
            <div class="flex items-center gap-1.5 text-xs text-gray-600 mb-1">
              <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
              {{ $supplier->contact_email }}
            </div>
            @endif
            @if($supplier->contact_phone)
            <div class="flex items-center gap-1.5 text-xs text-gray-600">
              <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
              {{ $supplier->contact_phone }}
            </div>
            @endif
            @if(!$supplier->contact_email && !$supplier->contact_phone)
            <span class="text-xs text-gray-300 font-medium">—</span>
            @endif
          </td>

          <td class="px-6 py-4 text-center">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-xs font-black text-gray-700">
              {{ $supplier->products_count }}
            </span>
          </td>

          <td class="px-6 py-4 text-center">
            @if($supplier->is_verified)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-50 text-blue-700 border border-blue-100">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
              Verified
            </span>
            @else
            <span class="text-xs text-gray-300 font-medium">&mdash;</span>
            @endif
          </td>

          <td class="px-6 py-4 text-center">
              @csrf
              <button type="submit" title="Toggle status"
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest transition-all
                  {{ $supplier->is_active
                      ? 'bg-green-50 text-green-700 border border-green-100 hover:bg-green-100'
                      : 'bg-amber-50 text-amber-700 border border-amber-100 hover:bg-amber-100' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $supplier->is_active ? 'bg-green-500' : 'bg-amber-500' }}"></span>
                {{ $supplier->is_active ? 'Active' : 'Inactive' }}
              </button>
            </form>
          </td>

          <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <a href="{{ route('admin.suppliers.edit', $supplier) }}" title="Edit" class="p-2 text-gray-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
              </a>
              <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" class="inline" onsubmit="return confirm('Delete {{ addslashes($supplier->name) }}? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" title="Delete" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-6 py-20 text-center">
            <div class="flex flex-col items-center gap-4">
              <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
              <p class="text-gray-400 font-black text-xs uppercase tracking-widest">No partners found</p>
              <a href="{{ route('admin.suppliers.create') }}" class="btn-primary text-xs px-5 py-2.5">Add First Partner</a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($suppliers->hasPages())
  <div class="mt-6">
    {{ $suppliers->withQueryString()->links() }}
  </div>
  @endif

</div>
@endsection
