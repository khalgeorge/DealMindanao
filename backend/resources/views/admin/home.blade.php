@extends('layouts.admin')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p class="text-sm text-gray-500">Quick overview of activity.</p>
    </div>
</div>

<div class="space-y-6">
    <div class="card">
        <h2 class="text-lg font-semibold mb-4">Latest Product</h2>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-medium py-4">Sample Product</td>
                        <td class="py-4 text-brand font-semibold">₱999</td>
                        <td class="py-4">
                            <span class="badge-success">Active</span>
                        </td>
                        <td class="text-right pr-4 py-4">
                            <div class="flex flex-wrap gap-2 justify-end">
                                <a href="#" class="btn-secondary">Edit</a>
                                <a href="#" class="btn-danger">Delete</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card">
            <p class="stat-label">Products</p>
            <p class="stat-value">24</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Orders</p>
            <p class="stat-value">12</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Companies</p>
            <p class="stat-value">5</p>
        </div>
        <div class="stat-card">
            <p class="stat-label">Categories</p>
            <p class="stat-value">3</p>
        </div>
    </div>
</div>
@endsection
