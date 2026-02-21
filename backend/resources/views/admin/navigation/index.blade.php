@extends('layouts.admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900">Navigation</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Drag rows to reorder &bull; Changes save automatically</p>
    </div>
    <a href="{{ route('admin.navigation.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Menu Item
    </a>
</header>

<div class="admin-content">
    @if(session('success'))
        <div class="alert-success mb-6">{{ session('success') }}</div>
    @endif

    {{-- Save status toast --}}
    <div id="save-toast" style="display:none; position:fixed; bottom:24px; right:24px; z-index:9999;"
        class="flex items-center gap-3 bg-gray-900 text-white text-sm font-bold px-5 py-3 rounded-lg shadow-xl">
        <span id="save-toast-icon"></span>
        <span id="save-toast-msg"></span>
    </div>

    @php
        $grouped = $items->groupBy('location');
        $locations = ['header' => 'Header Menu', 'footer' => 'Footer Menu'];
    @endphp

    @foreach($locations as $loc => $locLabel)
        @if(isset($grouped[$loc]) && $grouped[$loc]->count())
        <div class="mb-8">
            <h2 class="text-xs font-black uppercase tracking-widest mb-3 {{ $loc === 'header' ? 'text-blue-600' : 'text-purple-600' }}">
                {{ $locLabel }}
            </h2>
            <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:40px;"></th>
                                <th>Label</th>
                                <th>URL</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="sortable-tbody" data-location="{{ $loc }}">
                            @foreach($grouped[$loc]->sortBy('position') as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors" data-id="{{ $item->id }}">
                                <td class="px-4 py-4 text-gray-300 cursor-grab active:cursor-grabbing drag-handle" title="Drag to reorder">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                    </svg>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $item->label }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $item->url }}</td>
                                <td class="px-6 py-4">
                                    <span class="pos-badge text-xs font-black bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $item->position }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->is_active)
                                        <span class="badge-success text-[10px]">Active</span>
                                    @else
                                        <span class="badge-secondary text-[10px]">Hidden</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="{{ route('admin.navigation.edit', $item) }}" class="text-brand-600 font-bold text-xs uppercase hover:underline">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('admin.navigation.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 font-bold text-xs uppercase hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    @if($items->isEmpty())
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm px-6 py-12 text-center">
            <div class="text-4xl mb-3">🧭</div>
            <p class="font-bold text-gray-400 italic">No menu items yet</p>
            <p class="text-xs text-gray-400 mt-1">Add your first navigation item to get started.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const reorderUrl = '{{ route('admin.navigation.reorder') }}';
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function showToast(msg, success = true) {
    const toast = document.getElementById('save-toast');
    document.getElementById('save-toast-msg').textContent = msg;
    document.getElementById('save-toast-icon').innerHTML = success
        ? '<svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>'
        : '<svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>';
    toast.style.display = 'flex';
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => toast.style.display = 'none', 2500);
}

function refreshPositionBadges(tbody) {
    tbody.querySelectorAll('tr').forEach((row, i) => {
        const badge = row.querySelector('.pos-badge');
        if (badge) badge.textContent = i + 1;
    });
}

async function saveOrder(tbody) {
    const rows = tbody.querySelectorAll('tr[data-id]');
    const items = Array.from(rows).map((row, i) => ({
        id: parseInt(row.dataset.id),
        position: i + 1,
    }));

    try {
        const res = await fetch(reorderUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ items }),
        });
        if (res.ok) {
            showToast('Order saved');
        } else {
            showToast('Failed to save', false);
        }
    } catch {
        showToast('Failed to save', false);
    }
}

document.querySelectorAll('.sortable-tbody').forEach(tbody => {
    Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'bg-brand-50',
        onEnd() {
            refreshPositionBadges(tbody);
            saveOrder(tbody);
        },
    });
});
</script>
@endpush
