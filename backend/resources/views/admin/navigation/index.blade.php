@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Navigation</h1>
        <p class="text-sm text-gray-500">Manage menu items and positions.</p>
    </div>
    <a href="/admin/navigation/create" class="btn-primary">+ Add Menu Item</a>
</div>

<div class="space-y-6">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($items->count())
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>URL</th>
                        <th>Location</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td class="font-medium py-4">{{ $item->label }}</td>
                            <td class="py-4 text-sm text-gray-600">{{ $item->url }}</td>
                            <td class="py-4">{{ ucfirst($item->location) }}</td>
                            <td class="py-4">{{ $item->position }}</td>
                            <td class="py-4">
                                @if($item->is_active)
                                    <span class="badge-success">Active</span>
                                @else
                                    <span class="badge-gray">Hidden</span>
                                @endif
                            </td>
                            <td class="text-right pr-4 py-4">
                                <div class="flex flex-wrap gap-2 justify-end">
                                    <a href="/admin/navigation/{{ $item->id }}/edit" class="btn-secondary">Edit</a>
                                    <form action="/admin/navigation/{{ $item->id }}" method="POST" onsubmit="return confirm('Delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $items->links() }}
        </div>
    @else
        <div class="card text-center p-10">
            <div class="text-5xl mb-3">🧭</div>
            <h2 class="text-xl font-semibold mb-2">No menu items yet</h2>
            <p class="text-gray-500">Add your first navigation item to get started.</p>
        </div>
    @endif
</div>
@endsection
