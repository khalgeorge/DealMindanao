@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<header class="admin-header">
  <div>
    <h1 class="text-xl font-black text-gray-900">Add Product</h1>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Create a new marketplace listing</p>
  </div>
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.products.index') }}" class="btn-secondary flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
      Back to Products
    </a>
  </div>
</header>

<div class="admin-content">
  <div class="max-w-4xl mx-auto">
    @include('admin.products._form', ['mode' => 'create', 'product' => null])
  </div>
</div>
@endsection
