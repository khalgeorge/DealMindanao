@extends('layouts.admin')

@section('title', 'Refund & Returns Policy Editor - DealMindanao Admin')

@section('content')
<div class="admin-content space-y-6">

    {{-- Page header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Refund &amp; Returns Policy Editor</h1>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5">Manage sections, header, and footer bar</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.settings.index') }}" class="btn-secondary btn-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Settings
            </a>
            <a href="{{ url('/refunds') }}" target="_blank"
               class="inline-flex items-center gap-2 text-xs font-black text-brand-600 hover:underline uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                View Live Page
            </a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="bg-brand-50 border border-brand-200 text-brand-800 text-sm font-bold px-5 py-3 rounded-lg">
        ✓ {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 text-sm font-bold px-5 py-3 rounded-lg">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         SECTION 1 — PAGE SETTINGS (header + footer bar)
    ══════════════════════════════════════════════════════════════════ --}}
    <form action="{{ route('admin.refund_policy.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Header card --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Header Settings</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Page title &amp; subtitle</p>
                    </div>
                </div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <span class="text-xs font-black text-gray-500 uppercase tracking-widest">Show Header</span>
                    <div class="relative">
                        <input type="checkbox" name="rp_header_enabled" class="sr-only peer" value="1"
                               {{ ($s['rp_header_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <div class="w-10 h-6 bg-gray-200 peer-checked:bg-brand-600 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-brand-500"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                </label>
            </div>
            <div class="p-8 grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Page Title <span class="text-red-500">*</span></label>
                    <input type="text" name="rp_title" value="{{ old('rp_title', $s['rp_title']) }}"
                           class="input" required placeholder="Refund &amp; Returns Policy">
                    @error('rp_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Subtitle / Description</label>
                    <input type="text" name="rp_subtitle" value="{{ old('rp_subtitle', $s['rp_subtitle']) }}"
                           class="input" placeholder="We want you to be satisfied…">
                </div>
            </div>
        </div>

        {{-- Footer info bar card --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Bottom Info Bar</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Support text + link shown below the sections</p>
                    </div>
                </div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <span class="text-xs font-black text-gray-500 uppercase tracking-widest">Show Bar</span>
                    <div class="relative">
                        <input type="checkbox" name="rp_footer_enabled" class="sr-only peer" value="1"
                               {{ ($s['rp_footer_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <div class="w-10 h-6 bg-gray-200 peer-checked:bg-brand-600 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-brand-500"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                </label>
            </div>
            <div class="p-8 space-y-4">
                <p class="text-xs text-gray-400">Renders as: <em>"[Support text] <span class="text-brand-600">[Link label]</span>."</em></p>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Support Text</label>
                        <input type="text" name="rp_footer_text" value="{{ old('rp_footer_text', $s['rp_footer_text']) }}"
                               class="input" placeholder="For questions about returns…">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link Label</label>
                        <input type="text" name="rp_footer_link_label" value="{{ old('rp_footer_link_label', $s['rp_footer_link_label']) }}"
                               class="input" placeholder="contact our support team">
                    </div>
                </div>
                <div class="max-w-sm">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link URL</label>
                    <input type="text" name="rp_footer_link_url" value="{{ old('rp_footer_link_url', $s['rp_footer_link_url']) }}"
                           class="input" placeholder="/contact">
                </div>
            </div>
        </div>

        {{-- SEO & META TAGS --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-violet-50 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">SEO</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Meta title, description &amp; keywords for search engines</p>
                </div>
            </div>
            <div class="p-8 space-y-5">
                <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 border border-blue-100 rounded-lg">
                    <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    <span class="text-xs font-bold text-blue-700 uppercase tracking-widest">Page URL:</span>
                    <a href="{{ url('/refunds') }}" target="_blank" class="text-xs font-black text-blue-600 hover:underline">{{ url('/refunds') }}</a>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Title <span class="font-normal normal-case">(max 70 chars)</span></label>
                    <input type="text" name="rp_meta_title" value="{{ old('rp_meta_title', $s['rp_meta_title']) }}" class="input" placeholder="Refund &amp; Returns Policy - DealMindanao" maxlength="70">
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 50–70 characters.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Description <span class="font-normal normal-case">(max 160 chars)</span></label>
                    <textarea name="rp_meta_description" rows="3" class="input resize-none" placeholder="Brief description for search engines…" maxlength="160">{{ old('rp_meta_description', $s['rp_meta_description']) }}</textarea>
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 150–160 characters.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Keywords <span class="font-normal normal-case">(comma-separated)</span></label>
                    <input type="text" name="rp_meta_keywords" value="{{ old('rp_meta_keywords', $s['rp_meta_keywords']) }}" class="input" placeholder="refund, returns, policy, DealMindanao">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Canonical URL <span class="font-normal normal-case">(optional)</span></label>
                    <input type="text" name="rp_canonical" value="{{ old('rp_canonical', $s['rp_canonical']) }}" class="input" placeholder="{{ url('/refunds') }}">
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Leave blank to use the default URL. Only set this if this page is accessible at multiple addresses.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Page Settings
            </button>
        </div>
    </form>

    {{-- ══════════════════════════════════════════════════════════════════
         SECTION 2 — POLICY SECTIONS LIST
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Policy Sections</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Drag to reorder · toggle to show/hide · click Edit to modify content</p>
                </div>
            </div>
            <span class="text-xs font-bold text-gray-400">{{ $sections->count() }} total</span>
        </div>

        <div id="sections-sortable" class="divide-y divide-gray-50">
            @forelse($sections as $index => $section)
            <div class="section-row flex items-start gap-4 px-8 py-5 hover:bg-gray-50/60 transition-colors"
                 data-id="{{ $section->id }}">

                {{-- Drag handle --}}
                <div class="drag-handle mt-1 cursor-grab text-gray-300 hover:text-gray-500 shrink-0 select-none" title="Drag to reorder">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                    </svg>
                </div>

                {{-- Section number --}}
                <div class="shrink-0 mt-0.5">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <span class="text-xs font-black text-gray-500">{{ $index + 1 }}</span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    {{-- View mode --}}
                    <div class="section-view">
                        <p class="text-sm font-black text-gray-900 leading-tight">{{ $section->title }}</p>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ Str::limit(strip_tags($section->body), 120) }}</p>
                    </div>

                    {{-- Edit mode --}}
                    <div class="section-edit hidden mt-3">
                        <form action="{{ route('admin.refund_policy.sections.update', $section->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Section Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" value="{{ $section->title }}" class="input text-sm" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
                                    Body Content <span class="text-red-500">*</span>
                                    <span class="normal-case font-normal text-gray-400 ml-1">— HTML supported (p, ul, ol, li, strong, a…)</span>
                                </label>
                                <textarea name="body" rows="8" class="textarea text-sm font-mono" required>{{ $section->body }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Sort Order</label>
                                    <input type="number" name="sort_order" value="{{ $section->sort_order }}" class="input text-sm" min="0">
                                </div>
                                <div class="flex items-end pb-1">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1"
                                               class="w-4 h-4 rounded accent-brand-600"
                                               {{ $section->is_active ? 'checked' : '' }}>
                                        <span class="text-sm font-bold text-gray-700">Active / Visible</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="btn-primary btn-sm">Save</button>
                                <button type="button" class="btn-secondary btn-sm section-cancel-edit">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 shrink-0 mt-0.5">
                    <form action="{{ route('admin.refund_policy.sections.toggle', $section->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-full transition-colors
                                       {{ $section->is_active ? 'bg-brand-50 text-brand-600 hover:bg-brand-100' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}"
                                title="{{ $section->is_active ? 'Click to hide' : 'Click to show' }}">
                            {{ $section->is_active ? 'Active' : 'Hidden' }}
                        </button>
                    </form>
                    <button type="button" class="section-edit-btn text-xs font-bold text-brand-600 hover:underline">Edit</button>
                    <form action="{{ route('admin.refund_policy.sections.destroy', $section->id) }}" method="POST"
                          onsubmit="return confirm('Delete this section?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-8 py-12 text-center text-sm text-gray-400">
                No sections yet. Add your first one below.
            </div>
            @endforelse
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         SECTION 3 — ADD NEW SECTION
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Add New Section</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.refund_policy.sections.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                        Section Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="input" required placeholder="e.g. Return Eligibility">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                        Body Content <span class="text-red-500">*</span>
                        <span class="normal-case font-normal text-gray-400 ml-1">— HTML supported</span>
                    </label>
                    <textarea name="body" rows="8" class="textarea font-mono" required
                              placeholder="<p class=&quot;text-gray-600 leading-relaxed&quot;>Your content here…</p>">{{ old('body') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">
                        Use: <code class="bg-gray-100 px-1 rounded">text-gray-600 leading-relaxed</code> on &lt;p&gt;,
                        <code class="bg-gray-100 px-1 rounded">list-disc list-inside text-gray-600 space-y-2</code> on &lt;ul&gt;,
                        <code class="bg-gray-100 px-1 rounded">list-decimal list-inside text-gray-600 space-y-3</code> on &lt;ol&gt;.
                    </p>
                </div>
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Section
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ── Inline edit toggle ──────────────────────────────────────────────────────
document.querySelectorAll('.section-edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const row = btn.closest('.section-row');
        row.querySelector('.section-view').classList.add('hidden');
        row.querySelector('.section-edit').classList.remove('hidden');
        btn.classList.add('hidden');
    });
});
document.querySelectorAll('.section-cancel-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        const row = btn.closest('.section-row');
        row.querySelector('.section-view').classList.remove('hidden');
        row.querySelector('.section-edit').classList.add('hidden');
        row.querySelector('.section-edit-btn').classList.remove('hidden');
    });
});

// ── Drag-and-drop reorder ───────────────────────────────────────────────────
(function () {
    const list = document.getElementById('sections-sortable');
    if (!list) return;

    let dragging = null;

    list.querySelectorAll('.section-row').forEach(row => {
        const handle = row.querySelector('.drag-handle');
        handle.addEventListener('mousedown', () => { row.draggable = true; });
        handle.addEventListener('mouseup',   () => { row.draggable = false; });

        row.addEventListener('dragstart', e => {
            dragging = row;
            e.dataTransfer.effectAllowed = 'move';
            setTimeout(() => row.classList.add('opacity-50'), 0);
        });
        row.addEventListener('dragend', () => {
            row.classList.remove('opacity-50');
            row.draggable = false;
            dragging = null;
            saveOrder();
        });
        row.addEventListener('dragover', e => {
            e.preventDefault();
            if (dragging && dragging !== row) {
                const mid = row.getBoundingClientRect().top + row.getBoundingClientRect().height / 2;
                list.insertBefore(dragging, e.clientY < mid ? row : row.nextSibling);
            }
        });
    });

    function saveOrder() {
        const ids = [...list.querySelectorAll('.section-row')].map(r => parseInt(r.dataset.id));
        fetch('{{ route('admin.refund_policy.sections.reorder') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ order: ids }),
        });
    }
})();
</script>
@endpush
