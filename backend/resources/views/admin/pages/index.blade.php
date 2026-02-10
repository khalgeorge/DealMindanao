@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Pages</h1>
        <p class="text-sm text-gray-500">Manage static pages and content.</p>
    </div>
    <a href="/admin/pages/create" class="btn-primary">+ Add Page</a>
</div>

<div class="space-y-6">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($pages->count())
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Updated</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $page)
                        <tr>
                            <td class="font-medium py-4">{{ $page->title }}</td>
                            <td class="py-4 text-sm text-gray-600">{{ $page->slug }}</td>
                            <td class="py-4 text-sm text-gray-500">{{ $page->updated_at?->format('M d, Y') ?? '-' }}</td>
                            <td class="text-right pr-4 py-4">
                                <div class="flex flex-wrap gap-2 justify-end">
                                    <a href="/pages/{{ $page->slug }}" target="_blank" class="btn-secondary">View</a>
                                    <a href="/admin/pages/{{ $page->id }}/edit" class="btn-secondary">Edit</a>
                                    <form action="/admin/pages/{{ $page->id }}" method="POST" onsubmit="return confirm('Delete this page?')">
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
            {{ $pages->links() }}
        </div>
    @else
        <div class="card text-center p-10">
            <div class="text-5xl mb-3">📄</div>
            <h2 class="text-xl font-semibold mb-2">No pages yet</h2>
            <p class="text-gray-500">Create your first static page to get started.</p>
        </div>
    @endif
</div>
@endsection
