@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Companies</h1>
        <p class="text-sm text-gray-500">Manage partners and contact details.</p>
    </div>
    <a href="/admin/companies/create" class="btn-primary">+ Add Company</a>
</div>

<div class="space-y-6">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($companies->count())
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Contact</th>
                        <th>Messenger</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td class="py-4">
                                @if($company->logo)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($company->logo) }}" alt="{{ $company->name }} logo" class="h-12 w-12 rounded-lg object-cover border border-gray-100">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gray-100"></div>
                                @endif
                            </td>
                            <td class="font-medium py-4">{{ $company->name }}</td>
                            <td class="py-4">{{ $company->city ?? '-' }}</td>
                            <td class="py-4">
                                <div class="text-sm">{{ $company->contact_email ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $company->contact_phone ?? '-' }}</div>
                            </td>
                            <td class="py-4">
                                @if($company->messenger_link)
                                    <a href="{{ $company->messenger_link }}" target="_blank" class="btn-secondary">Open</a>
                                @else
                                    <span class="text-xs text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="text-right pr-4 py-4">
                                <div class="flex flex-wrap gap-2 justify-end">
                                    <a href="/admin/companies/{{ $company->id }}/edit" class="btn-secondary">Edit</a>
                                    <form action="/admin/companies/{{ $company->id }}" method="POST" onsubmit="return confirm('Delete this company?')">
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
            {{ $companies->links() }}
        </div>
    @else
        <div class="card text-center p-10">
            <div class="text-5xl mb-3">🏢</div>
            <h2 class="text-xl font-semibold mb-2">No companies yet</h2>
            <p class="text-gray-500">Add your first partner to get started.</p>
        </div>
    @endif
</div>
@endsection
