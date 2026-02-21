@extends('layouts.admin')

@section('title', 'Trust & Safety Page Editor - DealMindanao Admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Trust &amp; Safety Page Editor</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5">Manage page content, items, and footer links</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.settings.index') }}" class="btn-secondary btn-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Settings
        </a>
        <a href="{{ url('/trust-safety') }}" target="_blank"
           class="inline-flex items-center gap-2 text-xs font-black text-brand-600 hover:underline uppercase tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            View Live Page
        </a>
    </div>
</header>

<div class="admin-content space-y-6">

    {{-- Flash message --}}
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

    {{-- ══════════════════════════════════════════════════════════════════════
         SECTION 1 — PAGE SETTINGS (header + footer)
    ══════════════════════════════════════════════════════════════════════ --}}
    <form action="{{ route('admin.trust_safety.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Header Card --}}
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
                        <input type="checkbox" name="ts_header_enabled" class="sr-only peer" value="1"
                               {{ ($s['ts_header_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <div class="w-10 h-6 bg-gray-200 peer-checked:bg-brand-600 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-brand-500"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                </label>
            </div>
            <div class="p-8 grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Page Title <span class="text-red-500">*</span></label>
                    <input type="text" name="ts_title" value="{{ old('ts_title', $s['ts_title']) }}"
                           class="input" required placeholder="Trust &amp; Safety">
                    @error('ts_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Subtitle <span class="text-red-500">*</span></label>
                    <input type="text" name="ts_subtitle" value="{{ old('ts_subtitle', $s['ts_subtitle']) }}"
                           class="input" required placeholder="Your confidence and security...">
                    @error('ts_subtitle')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Footer Section Card --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Bottom Info Section</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Text paragraph + 4 policy links</p>
                    </div>
                </div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <span class="text-xs font-black text-gray-500 uppercase tracking-widest">Show Section</span>
                    <div class="relative">
                        <input type="checkbox" name="ts_footer_enabled" class="sr-only peer" value="1"
                               {{ ($s['ts_footer_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        <div class="w-10 h-6 bg-gray-200 peer-checked:bg-brand-600 rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-brand-500"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                </label>
            </div>
            <div class="p-8 space-y-5">
                {{-- Paragraph text --}}
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Paragraph Text</p>
                    <p class="text-xs text-gray-400 mb-4">Renders as: <em>"[prefix text] <span class="text-brand-600">[contact link label]</span> [suffix text]"</em></p>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Text Before Link</label>
                            <input type="text" name="ts_footer_prefix" value="{{ old('ts_footer_prefix', $s['ts_footer_prefix']) }}"
                                   class="input" placeholder="For questions about...">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Contact Link Label</label>
                            <input type="text" name="ts_footer_contact_label" value="{{ old('ts_footer_contact_label', $s['ts_footer_contact_label']) }}"
                                   class="input" placeholder="contact our support team">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Text After Link</label>
                            <input type="text" name="ts_footer_suffix" value="{{ old('ts_footer_suffix', $s['ts_footer_suffix']) }}"
                                   class="input" placeholder="or explore our policies:">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Contact Link URL</label>
                        <input type="text" name="ts_footer_contact_url" value="{{ old('ts_footer_contact_url', $s['ts_footer_contact_url']) }}"
                               class="input max-w-sm" placeholder="/contact">
                    </div>
                </div>

                {{-- Policy Links --}}
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Policy Links (4)</p>
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach([1,2,3,4] as $n)
                        <div class="flex gap-3 items-start">
                            <div class="flex-1">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Link {{ $n }} Label</label>
                                <input type="text" name="ts_footer_link{{ $n }}_label"
                                       value="{{ old('ts_footer_link'.$n.'_label', $s['ts_footer_link'.$n.'_label']) }}"
                                       class="input" placeholder="e.g. Help Center →">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Link {{ $n }} URL</label>
                                <input type="text" name="ts_footer_link{{ $n }}_url"
                                       value="{{ old('ts_footer_link'.$n.'_url', $s['ts_footer_link'.$n.'_url']) }}"
                                       class="input" placeholder="/help">
                            </div>
                        </div>
                        @endforeach
                    </div>
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
                    <a href="{{ url('/trust-safety') }}" target="_blank" class="text-xs font-black text-blue-600 hover:underline">{{ url('/trust-safety') }}</a>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Title <span class="font-normal normal-case">(max 70 chars)</span></label>
                    <input type="text" name="ts_meta_title" value="{{ old('ts_meta_title', $s['ts_meta_title']) }}" class="input" placeholder="Trust &amp; Safety - DealMindanao" maxlength="70">
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 50–70 characters.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Description <span class="font-normal normal-case">(max 160 chars)</span></label>
                    <textarea name="ts_meta_description" rows="3" class="input resize-none" placeholder="Brief description for search engines…" maxlength="160">{{ old('ts_meta_description', $s['ts_meta_description']) }}</textarea>
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 150–160 characters.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Keywords <span class="font-normal normal-case">(comma-separated)</span></label>
                    <input type="text" name="ts_meta_keywords" value="{{ old('ts_meta_keywords', $s['ts_meta_keywords']) }}" class="input" placeholder="trust, safety, secure, DealMindanao">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Canonical URL <span class="font-normal normal-case">(optional)</span></label>
                    <input type="text" name="ts_canonical" value="{{ old('ts_canonical', $s['ts_canonical']) }}" class="input" placeholder="{{ url('/trust-safety') }}">
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

    {{-- ══════════════════════════════════════════════════════════════════════
         SECTION 2 — TRUST & SAFETY ITEMS
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Trust &amp; Safety Items</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Drag to reorder · toggle to show/hide · click Edit to update</p>
                </div>
            </div>
            <span class="text-xs font-bold text-gray-400">{{ $items->count() }} total</span>
        </div>

        <div id="items-sortable" class="divide-y divide-gray-50">
            @forelse($items as $item)
            <div class="item-row flex items-start gap-4 px-8 py-5 hover:bg-gray-50/60 transition-colors"
                 data-id="{{ $item->id }}">

                {{-- Drag handle --}}
                <div class="drag-handle mt-1 cursor-grab text-gray-300 hover:text-gray-500 shrink-0 select-none" title="Drag to reorder">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                    </svg>
                </div>

                {{-- Icon preview --}}
                <div class="shrink-0 mt-0.5">
                    <div class="w-10 h-10 bg-{{ $item->icon_color }}-100 rounded-lg flex items-center justify-center">
                        @if($item->icon_svg)
                        <svg class="w-5 h-5 text-{{ $item->icon_color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item->icon_svg }}"></path>
                        </svg>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    {{-- View mode --}}
                    <div class="item-view">
                        <p class="text-sm font-black text-gray-900 leading-tight">{{ $item->title }}</p>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $item->description }}</p>
                    </div>

                    {{-- Edit mode (hidden by default) --}}
                    <div class="item-edit hidden mt-3">
                        <form action="{{ route('admin.trust_safety.items.update', $item->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" value="{{ $item->title }}"
                                       class="input text-sm" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Description <span class="text-red-500">*</span></label>
                                <textarea name="description" rows="3" class="textarea text-sm" required>{{ $item->description }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Icon Color</label>
                                    <select name="icon_color" class="input text-sm">
                                        <option value="brand" {{ $item->icon_color === 'brand' ? 'selected' : '' }}>Brand (Green)</option>
                                        <option value="accent" {{ $item->icon_color === 'accent' ? 'selected' : '' }}>Accent (Amber)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Sort Order</label>
                                    <input type="number" name="sort_order" value="{{ $item->sort_order }}"
                                           class="input text-sm" min="0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Icon</label>
                                <select class="input text-sm item-icon-select" data-target="icon-svg-{{ $item->id }}">
                                    <option value="">— Select predefined icon —</option>
                                    <option value="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">🛡 Shield Check (Verified)</option>
                                    <option value="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">🔒 Lock (Security)</option>
                                    <option value="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">📋 Clipboard Check (Review)</option>
                                    <option value="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">👤 User (Data Protection)</option>
                                    <option value="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">🔧 Support (Disputes)</option>
                                    <option value="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">✅ Check Circle</option>
                                    <option value="M15 12a3 3 0 11-6 0 3 3 0 016 0zm2.846-2.372A10.954 10.954 0 0121 12c-1.872 3.804-5.814 6-9 6s-7.128-2.196-9-6a10.954 10.954 0 012.154-3.628">👁 Eye (Transparency)</option>
                                    <option value="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">📄 Document (Policy)</option>
                                </select>
                                <textarea name="icon_svg" id="icon-svg-{{ $item->id }}" rows="2"
                                          class="textarea text-xs font-mono mt-2"
                                          placeholder="Or paste a custom SVG path d=&quot;...&quot; value">{{ $item->icon_svg }}</textarea>
                            </div>
                            <div class="flex items-center gap-3 pt-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded accent-brand-600"
                                           {{ $item->is_active ? 'checked' : '' }}>
                                    <span class="text-sm font-bold text-gray-700">Active / Visible</span>
                                </label>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="btn-primary btn-sm">Save</button>
                                <button type="button" class="btn-secondary btn-sm item-cancel-edit">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 shrink-0 mt-0.5">
                    <form action="{{ route('admin.trust_safety.items.toggle', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-full transition-colors
                                       {{ $item->is_active ? 'bg-brand-50 text-brand-600 hover:bg-brand-100' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}"
                                title="{{ $item->is_active ? 'Click to hide' : 'Click to show' }}">
                            {{ $item->is_active ? 'Active' : 'Hidden' }}
                        </button>
                    </form>
                    <button type="button" class="item-edit-btn text-xs font-bold text-brand-600 hover:underline">Edit</button>
                    <form action="{{ route('admin.trust_safety.items.destroy', $item->id) }}" method="POST"
                          onsubmit="return confirm('Delete this item?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-8 py-12 text-center text-sm text-gray-400">
                No items yet. Add your first one below.
            </div>
            @endforelse
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════
         SECTION 3 — ADD NEW ITEM
    ══════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Add New Item</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.trust_safety.items.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="input" required placeholder="e.g. We Verify Product Listings">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="3" class="textarea" required
                                  placeholder="Explain this trust or safety point...">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Icon Color <span class="text-red-500">*</span></label>
                        <select name="icon_color" class="input" required>
                            <option value="brand" {{ old('icon_color') === 'brand' ? 'selected' : '' }}>Brand (Green)</option>
                            <option value="accent" {{ old('icon_color') === 'accent' ? 'selected' : '' }}>Accent (Amber)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $items->count()) }}"
                               class="input" min="0">
                        <p class="text-xs text-gray-400 mt-1">Lower numbers appear first.</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Icon</label>
                        <select class="input item-icon-select mb-2" data-target="new-icon-svg">
                            <option value="">— Select predefined icon —</option>
                            <option value="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">🛡 Shield Check (Verified)</option>
                            <option value="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">🔒 Lock (Security)</option>
                            <option value="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">📋 Clipboard Check (Review)</option>
                            <option value="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z">👤 User (Data Protection)</option>
                            <option value="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">🔧 Support (Disputes)</option>
                            <option value="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">✅ Check Circle</option>
                            <option value="M15 12a3 3 0 11-6 0 3 3 0 016 0zm2.846-2.372A10.954 10.954 0 0121 12c-1.872 3.804-5.814 6-9 6s-7.128-2.196-9-6a10.954 10.954 0 012.154-3.628">👁 Eye (Transparency)</option>
                            <option value="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">📄 Document (Policy)</option>
                        </select>
                        <textarea name="icon_svg" id="new-icon-svg" rows="2"
                                  class="textarea text-xs font-mono"
                                  placeholder="SVG path d=&quot;...&quot; value (auto-filled when you pick an icon above)">{{ old('icon_svg') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Pick a predefined icon above, or paste a custom Heroicons SVG path value.</p>
                    </div>
                </div>
                <button type="submit" class="btn-primary flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Item
                </button>
            </form>
        </div>
    </div>

</div>{{-- /admin-content --}}
@endsection

@push('scripts')
<script>
// ── Inline edit toggle ──────────────────────────────────────────────────────
document.querySelectorAll('.item-edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const row = btn.closest('.item-row');
        row.querySelector('.item-view').classList.add('hidden');
        row.querySelector('.item-edit').classList.remove('hidden');
        btn.classList.add('hidden');
    });
});
document.querySelectorAll('.item-cancel-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        const row = btn.closest('.item-row');
        row.querySelector('.item-view').classList.remove('hidden');
        row.querySelector('.item-edit').classList.add('hidden');
        row.querySelector('.item-edit-btn').classList.remove('hidden');
    });
});

// ── Icon select → populate textarea ────────────────────────────────────────
document.querySelectorAll('.item-icon-select').forEach(sel => {
    sel.addEventListener('change', () => {
        const targetId = sel.dataset.target;
        const target = document.getElementById(targetId);
        if (target && sel.value) {
            target.value = sel.value;
        }
    });
});

// ── Drag-and-drop reorder ───────────────────────────────────────────────────
(function () {
    const list = document.getElementById('items-sortable');
    if (!list) return;

    let dragging = null;

    list.querySelectorAll('.item-row').forEach(row => {
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
        const ids = [...list.querySelectorAll('.item-row')].map(r => parseInt(r.dataset.id));
        fetch('{{ route('admin.trust_safety.items.reorder') }}', {
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
