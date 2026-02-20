@extends('layouts.admin')

@section('title', 'Help Page Editor - DealMindanao Admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Help Page Editor</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">FAQs, header &amp; CTA — /help</p>
    </div>
    <a href="{{ url('/help') }}" target="_blank" class="btn-secondary btn-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
        </svg>
        Preview Page
    </a>
</header>

<div class="admin-content space-y-8">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════
         SECTION 1 — PAGE SETTINGS (header + CTA + contact info)
    ══════════════════════════════════════════════════════════════════════ --}}
    <form action="{{ route('admin.help_page.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Header Card --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Page Header</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Title · subtitle</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="help_header_enabled" value="1" class="sr-only peer"
                           {{ ($s['help_header_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                    <span class="ml-3 text-sm font-bold text-gray-700">Enabled</span>
                </label>
            </div>
            <div class="p-8 grid md:grid-cols-1 gap-5">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Page Title <span class="text-red-500">*</span></label>
                    <input type="text" name="help_title" value="{{ old('help_title', $s['help_title']) }}"
                           class="input" required placeholder="Help Center">
                    @error('help_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Subtitle / Description <span class="text-red-500">*</span></label>
                    <textarea name="help_subtitle" rows="2" class="textarea" required
                              placeholder="Find answers to frequently asked questions...">{{ old('help_subtitle', $s['help_subtitle']) }}</textarea>
                    @error('help_subtitle')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- CTA Card --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.952 9.168-4.938"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Bottom CTA Section</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">"Still have questions?" card · 2 buttons</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="help_cta_enabled" value="1" class="sr-only peer"
                           {{ ($s['help_cta_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                    <span class="ml-3 text-sm font-bold text-gray-700">Enabled</span>
                </label>
            </div>
            <div class="p-8 space-y-5">
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Section Title <span class="text-red-500">*</span></label>
                        <input type="text" name="help_cta_title" value="{{ old('help_cta_title', $s['help_cta_title']) }}"
                               class="input" required placeholder="Still have questions?">
                        @error('help_cta_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Description <span class="text-red-500">*</span></label>
                        <input type="text" name="help_cta_description" value="{{ old('help_cta_description', $s['help_cta_description']) }}"
                               class="input" required placeholder="Our support team is here to help...">
                        @error('help_cta_description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="p-5 bg-gray-50 rounded-lg border border-gray-100 space-y-3">
                        <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Primary Button</p>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Label <span class="text-red-500">*</span></label>
                            <input type="text" name="help_cta_btn1_label" value="{{ old('help_cta_btn1_label', $s['help_cta_btn1_label']) }}"
                                   class="input" required placeholder="Contact Support">
                            @error('help_cta_btn1_label')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link URL <span class="text-red-500">*</span></label>
                            <input type="text" name="help_cta_btn1_link" value="{{ old('help_cta_btn1_link', $s['help_cta_btn1_link']) }}"
                                   class="input" required placeholder="/contact">
                            @error('help_cta_btn1_link')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="p-5 bg-gray-50 rounded-lg border border-gray-100 space-y-3">
                        <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Secondary Button</p>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Label <span class="text-red-500">*</span></label>
                            <input type="text" name="help_cta_btn2_label" value="{{ old('help_cta_btn2_label', $s['help_cta_btn2_label']) }}"
                                   class="input" required placeholder="Browse Deals">
                            @error('help_cta_btn2_label')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link URL <span class="text-red-500">*</span></label>
                            <input type="text" name="help_cta_btn2_link" value="{{ old('help_cta_btn2_link', $s['help_cta_btn2_link']) }}"
                                   class="input" required placeholder="/shop">
                            @error('help_cta_btn2_link')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Save Settings --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary btn-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Page Settings
            </button>
        </div>
    </form>

    {{-- ══════════════════════════════════════════════════════════════════════
         SECTION 2 — FAQ LIST
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">FAQ Items</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Drag to reorder · toggle to show/hide · click Edit to update</p>
                </div>
            </div>
            <span class="text-xs font-bold text-gray-400">{{ $faqs->count() }} total</span>
        </div>

        {{-- FAQ rows (drag-and-drop sortable) --}}
        <div id="faq-sortable" class="divide-y divide-gray-50">
            @forelse($faqs as $faq)
            <div class="faq-row flex items-start gap-4 px-8 py-5 hover:bg-gray-50/60 transition-colors"
                 data-id="{{ $faq->id }}">
                {{-- Drag handle --}}
                <div class="drag-handle mt-1 cursor-grab text-gray-300 hover:text-gray-500 shrink-0 select-none" title="Drag to reorder">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                    </svg>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    {{-- View mode --}}
                    <div class="faq-view">
                        <p class="text-sm font-black text-gray-900 leading-tight">{{ $faq->question }}</p>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ strip_tags($faq->answer) }}</p>
                    </div>
                    {{-- Edit mode (hidden by default) --}}
                    <div class="faq-edit hidden mt-3">
                        <form action="{{ route('admin.help_page.faqs.update', $faq->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Question <span class="text-red-500">*</span></label>
                                <input type="text" name="question" value="{{ $faq->question }}"
                                       class="input text-sm" required placeholder="FAQ question...">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Answer (HTML allowed) <span class="text-red-500">*</span></label>
                                <textarea name="answer" rows="4" class="textarea text-sm font-mono" required
                                          placeholder="Answer text. You may use HTML for links, <ul>, <strong>, etc.">{{ $faq->answer }}</textarea>
                                <p class="text-[10px] text-gray-400 mt-1">Plain text or basic HTML (links, lists, bold). Example: <code class="bg-gray-100 px-1 rounded">&lt;a href="/refunds"&gt;Refunds Policy&lt;/a&gt;</code></p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Sort Order</label>
                                    <input type="number" name="sort_order" value="{{ $faq->sort_order }}"
                                           class="input text-sm" min="0" placeholder="0">
                                </div>
                                <div class="flex items-end pb-1">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded accent-brand-600"
                                               {{ $faq->is_active ? 'checked' : '' }}>
                                        <span class="text-sm font-bold text-gray-700">Active / Visible</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex gap-3 pt-1">
                                <button type="submit" class="btn-primary btn-sm">Save</button>
                                <button type="button" class="btn-secondary btn-sm faq-cancel-edit">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 shrink-0 mt-0.5">
                    {{-- Active badge --}}
                    <form action="{{ route('admin.help_page.faqs.toggle', $faq->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-full transition-colors
                                       {{ $faq->is_active ? 'bg-brand-50 text-brand-600 hover:bg-brand-100' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}"
                                title="{{ $faq->is_active ? 'Click to hide' : 'Click to show' }}">
                            {{ $faq->is_active ? 'Active' : 'Hidden' }}
                        </button>
                    </form>
                    {{-- Edit toggle --}}
                    <button type="button" class="faq-edit-btn text-xs font-bold text-brand-600 hover:underline">Edit</button>
                    {{-- Delete --}}
                    <form action="{{ route('admin.help_page.faqs.destroy', $faq->id) }}" method="POST"
                          onsubmit="return confirm('Delete this FAQ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-8 py-12 text-center text-sm text-gray-400">
                No FAQs yet. Add your first one below.
            </div>
            @endforelse
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         SECTION 3 — ADD NEW FAQ
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Add New FAQ</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.help_page.faqs.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Question <span class="text-red-500">*</span></label>
                    <input type="text" name="question" value="{{ old('question') }}"
                           class="input" required placeholder="e.g. How does payment work?">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Answer (HTML allowed) <span class="text-red-500">*</span></label>
                    <textarea name="answer" rows="5" class="textarea font-mono" required
                              placeholder="Answer text. May include HTML for links, lists, bold...">{{ old('answer') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Plain text or basic HTML. For email links: <code class="bg-gray-100 px-1 rounded">&lt;a href="mailto:hello@dealmindanao.ph" class="text-brand-600 hover:underline font-semibold"&gt;hello@dealmindanao.ph&lt;/a&gt;</code></p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $faqs->count()) }}"
                               class="input" min="0" placeholder="0">
                        <p class="text-xs text-gray-400 mt-1">Lower numbers appear first.</p>
                    </div>
                </div>
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add FAQ
                </button>
            </form>
        </div>
    </div>

</div>{{-- /admin-content --}}
@endsection

@push('scripts')
<script>
// ── Inline edit toggle ──────────────────────────────────────────────────────
document.querySelectorAll('.faq-edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const row = btn.closest('.faq-row');
        row.querySelector('.faq-view').classList.add('hidden');
        row.querySelector('.faq-edit').classList.remove('hidden');
        btn.classList.add('hidden');
    });
});
document.querySelectorAll('.faq-cancel-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        const row = btn.closest('.faq-row');
        row.querySelector('.faq-view').classList.remove('hidden');
        row.querySelector('.faq-edit').classList.add('hidden');
        row.querySelector('.faq-edit-btn').classList.remove('hidden');
    });
});

// ── Drag-and-drop reorder (native HTML5, no library needed) ────────────────
(function () {
    const list = document.getElementById('faq-sortable');
    if (!list) return;

    let dragging = null;

    list.querySelectorAll('.faq-row').forEach(row => {
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
                const rect     = row.getBoundingClientRect();
                const midpoint = rect.top + rect.height / 2;
                if (e.clientY < midpoint) {
                    list.insertBefore(dragging, row);
                } else {
                    list.insertBefore(dragging, row.nextSibling);
                }
            }
        });
    });

    function saveOrder() {
        const ids = [...list.querySelectorAll('.faq-row')].map(r => parseInt(r.dataset.id));
        fetch('{{ route('admin.help_page.faqs.reorder') }}', {
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
