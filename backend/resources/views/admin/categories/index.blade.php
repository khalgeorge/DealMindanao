@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Categories</h1>
        <p class="text-sm text-gray-500">Manage product categories.</p>
    </div>
    <a href="/admin/categories/create" class="btn-primary">+ Add Category</a>
</div>

<div class="space-y-6">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @if($categories->count())
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="font-medium py-4">{{ $category->name }}</td>
                            <td class="py-4 text-sm text-gray-600">{{ $category->slug }}</td>
                            <td class="py-4">{{ $category->products_count }}</td>
                            <td class="text-right pr-4 py-4">
                                <div class="flex flex-wrap gap-2 justify-end">
                                    <a href="/admin/categories/{{ $category->id }}/edit" class="btn-secondary">Edit</a>
                                    <form action="/admin/categories/{{ $category->id }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger" type="submit" @disabled($category->products_count > 0) title="{{ $category->products_count > 0 ? 'Remove products from this category first.' : '' }}">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $categories->links() }}
        </div>
    @else
        <div class="card text-center p-10">
            <div class="text-5xl mb-3">🏷️</div>
            <h2 class="text-xl font-semibold mb-2">No categories yet</h2>
            <p class="text-gray-500">Add your first category to get started.</p>
        </div>
    @endif
</div>
@endsection
