@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Page Settings</h1>
        <p class="text-sm text-gray-500">Update static page content and branding assets.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Page</th>
                <th>Title</th>
                <th>Last Updated</th>
                <th class="text-right pr-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allowed as $slug => $label)
                @php
                    $page = $pages->get($slug);
                @endphp
                <tr>
                    <td class="py-4 font-medium">{{ $label }}</td>
                    <td class="py-4">{{ $page?->title ?? 'Not set' }}</td>
                    <td class="py-4 text-sm text-gray-500">
                        {{ $page?->updated_at?->format('M d, Y') ?? '-' }}
                    </td>
                    <td class="text-right pr-4 py-4">
                        <a href="/admin/settings/pages/{{ $slug }}/edit" class="btn-secondary">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
