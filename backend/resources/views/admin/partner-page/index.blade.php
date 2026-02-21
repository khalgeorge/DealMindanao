@extends('layouts.admin')

@section('title', 'Partner Page Editor - DealMindanao Admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Partner Page Editor</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">All content on /partner</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.settings.index') }}" class="btn-secondary btn-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Settings
        </a>
        <a href="{{ url('/partner') }}" target="_blank" class="btn-secondary btn-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            Preview Page
        </a>
    </div>
</header>

<div class="admin-content">

    @if(session('success'))
        <div class="alert-success mb-6">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error mb-6">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.partner_page.update') }}" method="POST" class="space-y-8">
        @csrf

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 1 — HERO
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            {{-- Section Header --}}
            <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Hero Section</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Dark banner · badge · heading · description · CTA button</p>
                    </div>
                </div>
                {{-- Enable/disable toggle --}}
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="partner_hero_enabled" value="1" class="sr-only peer"
                           {{ ($s['partner_hero_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                    <span class="ml-3 text-sm font-bold text-gray-700">Enabled</span>
                </label>
            </div>
            <div class="p-8 space-y-5">
                {{-- Badge --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Badge Label</label>
                    <input type="text" name="partner_hero_badge"
                           value="{{ old('partner_hero_badge', $s['partner_hero_badge']) }}"
                           class="input" placeholder="e.g. Partner Program">
                    <p class="text-xs text-gray-400 mt-1">Small pill shown above the heading. Leave blank to hide.</p>
                    @error('partner_hero_badge')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                {{-- Heading --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                            Heading — Plain Part <span class="text-red-500">*</span>
                            <span class="normal-case font-medium text-gray-300 ml-1">(white)</span>
                        </label>
                        <input type="text" name="partner_hero_title"
                               value="{{ old('partner_hero_title', $s['partner_hero_title']) }}"
                               class="input" required placeholder="e.g. Become a">
                        @error('partner_hero_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                            Heading — Highlight Part <span class="text-red-500">*</span>
                            <span class="normal-case font-medium text-gray-300 ml-1">(brand green)</span>
                        </label>
                        <input type="text" name="partner_hero_title_highlight"
                               value="{{ old('partner_hero_title_highlight', $s['partner_hero_title_highlight']) }}"
                               class="input" required placeholder="e.g. Partner">
                        @error('partner_hero_title_highlight')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                {{-- Heading preview --}}
                <div class="px-4 py-3 bg-gray-900 rounded-lg text-sm">
                    <span class="font-bold text-white">{{ $s['partner_hero_title'] }}</span>
                    <span class="font-bold text-brand-400"> {{ $s['partner_hero_title_highlight'] }}</span>
                </div>
                {{-- Description --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea name="partner_hero_description" rows="4" class="textarea" required
                              placeholder="Hero description...">{{ old('partner_hero_description', $s['partner_hero_description']) }}</textarea>
                    @error('partner_hero_description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                {{-- CTA Button --}}
                <div class="p-5 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">CTA Button</p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Button Label <span class="text-red-500">*</span></label>
                            <input type="text" name="partner_hero_cta_label"
                                   value="{{ old('partner_hero_cta_label', $s['partner_hero_cta_label']) }}"
                                   class="input" required placeholder="e.g. Become a Partner">
                            @error('partner_hero_cta_label')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link URL <span class="text-red-500">*</span></label>
                            <input type="text" name="partner_hero_cta_link"
                                   value="{{ old('partner_hero_cta_link', $s['partner_hero_cta_link']) }}"
                                   class="input" required placeholder="e.g. #apply">
                            <p class="text-xs text-gray-400 mt-1">Use <code class="bg-gray-100 px-1 rounded">#apply</code> to scroll to the CTA section on the same page.</p>
                            @error('partner_hero_cta_link')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 2 — FEATURE HIGHLIGHTS
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            {{-- Section Header --}}
            <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Feature Highlights</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">3 numbered cards · each can be individually enabled/disabled</p>
                    </div>
                </div>
                {{-- Section-level toggle --}}
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="partner_features_enabled" value="1" class="sr-only peer"
                           {{ ($s['partner_features_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                    <span class="ml-3 text-sm font-bold text-gray-700">Enabled</span>
                </label>
            </div>
            <div class="p-8 space-y-6">

                @foreach([
                    ['1', '01', 'Card 1'],
                    ['2', '02', 'Card 2'],
                    ['3', '03', 'Card 3'],
                ] as [$n, $defaultNum, $label])
                <div class="p-6 bg-gray-50 rounded-lg border border-gray-100">
                    {{-- Card header: number badge + card label + enable toggle --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-brand-50 text-brand-600 rounded-lg flex items-center justify-center font-black text-base italic shadow-sm">
                                {{ $s['partner_card'.$n.'_number'] }}
                            </div>
                            <p class="text-sm font-black text-gray-800 uppercase tracking-tight">{{ $label }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="partner_card{{ $n }}_enabled" value="1" class="sr-only peer"
                                   {{ ($s['partner_card'.$n.'_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-600"></div>
                            <span class="ml-2 text-xs font-bold text-gray-600">Show</span>
                        </label>
                    </div>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Number Label <span class="text-red-500">*</span></label>
                            <input type="text" name="partner_card{{ $n }}_number"
                                   value="{{ old('partner_card'.$n.'_number', $s['partner_card'.$n.'_number']) }}"
                                   class="input" required placeholder="{{ $defaultNum }}">
                            @error('partner_card'.$n.'_number')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="partner_card{{ $n }}_title"
                                   value="{{ old('partner_card'.$n.'_title', $s['partner_card'.$n.'_title']) }}"
                                   class="input" required placeholder="Card title">
                            @error('partner_card'.$n.'_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Description <span class="text-red-500">*</span></label>
                            <textarea name="partner_card{{ $n }}_description" rows="3" class="textarea" required
                                      placeholder="Card description...">{{ old('partner_card'.$n.'_description', $s['partner_card'.$n.'_description']) }}</textarea>
                            @error('partner_card'.$n.'_description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                @endforeach

                <p class="text-xs text-gray-400 font-medium">
                    <svg class="w-3 h-3 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Disabling individual cards will hide them from the grid. Disabling the section hides all 3 cards regardless of individual toggles.
                </p>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 3 — CTA / TESTIMONIAL
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            {{-- Section Header --}}
            <div class="px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.952 9.168-4.938"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">CTA / Testimonial Section</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Green-bg section with title, quote &amp; 2 buttons · anchor <code class="text-gray-500 bg-gray-100 px-1 rounded">#apply</code></p>
                    </div>
                </div>
                {{-- Enable/disable toggle --}}
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="partner_cta_enabled" value="1" class="sr-only peer"
                           {{ ($s['partner_cta_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                    <span class="ml-3 text-sm font-bold text-gray-700">Enabled</span>
                </label>
            </div>
            <div class="p-8 space-y-5">
                {{-- Title --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Section Title <span class="text-red-500">*</span></label>
                    <input type="text" name="partner_cta_title"
                           value="{{ old('partner_cta_title', $s['partner_cta_title']) }}"
                           class="input" required placeholder="e.g. Ready to Scale?">
                    @error('partner_cta_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                {{-- Quote / Testimonial --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Quote / Testimonial <span class="text-red-500">*</span></label>
                    <textarea name="partner_cta_quote" rows="3" class="textarea" required
                              placeholder='"Testimonial text here..."'>{{ old('partner_cta_quote', $s['partner_cta_quote']) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Displayed in italic below the title. Include surrounding quotes in the text if desired.</p>
                    @error('partner_cta_quote')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                {{-- Buttons --}}
                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Primary button --}}
                    <div class="p-5 bg-gray-50 rounded-lg border border-gray-100 space-y-3">
                        <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Primary Button</p>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Label <span class="text-red-500">*</span></label>
                            <input type="text" name="partner_cta_btn1_label"
                                   value="{{ old('partner_cta_btn1_label', $s['partner_cta_btn1_label']) }}"
                                   class="input" required placeholder="e.g. Contact Us to Partner">
                            @error('partner_cta_btn1_label')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link URL <span class="text-red-500">*</span></label>
                            <input type="text" name="partner_cta_btn1_link"
                                   value="{{ old('partner_cta_btn1_link', $s['partner_cta_btn1_link']) }}"
                                   class="input" required placeholder="e.g. /contact">
                            @error('partner_cta_btn1_link')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    {{-- Secondary button --}}
                    <div class="p-5 bg-gray-50 rounded-lg border border-gray-100 space-y-3">
                        <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Secondary Button</p>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Label <span class="text-red-500">*</span></label>
                            <input type="text" name="partner_cta_btn2_label"
                                   value="{{ old('partner_cta_btn2_label', $s['partner_cta_btn2_label']) }}"
                                   class="input" required placeholder="e.g. Learn About Us">
                            @error('partner_cta_btn2_label')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link URL <span class="text-red-500">*</span></label>
                            <input type="text" name="partner_cta_btn2_link"
                                   value="{{ old('partner_cta_btn2_link', $s['partner_cta_btn2_link']) }}"
                                   class="input" required placeholder="e.g. /about">
                            @error('partner_cta_btn2_link')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SEO & META TAGS
        ══════════════════════════════════════════════════════════════ --}}
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
                    <a href="{{ url('/partner') }}" target="_blank" class="text-xs font-black text-blue-600 hover:underline">{{ url('/partner') }}</a>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Title <span class="font-normal normal-case">(max 70 chars)</span></label>
                    <input type="text" name="partner_meta_title" value="{{ old('partner_meta_title', $s['partner_meta_title']) }}" class="input" placeholder="Become a Partner - DealMindanao" maxlength="70">
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 50–70 characters.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Description <span class="font-normal normal-case">(max 160 chars)</span></label>
                    <textarea name="partner_meta_description" rows="3" class="input resize-none" placeholder="Brief description for search engines…" maxlength="160">{{ old('partner_meta_description', $s['partner_meta_description']) }}</textarea>
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 150–160 characters.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Keywords <span class="font-normal normal-case">(comma-separated)</span></label>
                    <input type="text" name="partner_meta_keywords" value="{{ old('partner_meta_keywords', $s['partner_meta_keywords']) }}" class="input" placeholder="partner, supplier, Mindanao">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Canonical URL <span class="font-normal normal-case">(optional)</span></label>
                    <input type="text" name="partner_canonical" value="{{ old('partner_canonical', $s['partner_canonical']) }}" class="input" placeholder="{{ url('/partner') }}">
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Leave blank to use the default URL. Only set this if this page is accessible at multiple addresses.</p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SAVE BAR
        ══════════════════════════════════════════════════════════════ --}}
        <div class="flex items-center gap-4 pt-2 pb-8">
            <button type="submit" class="btn-primary btn-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save All Changes
            </button>
            <a href="{{ url('/partner') }}" target="_blank" class="btn-secondary btn-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                View Live Page
            </a>
        </div>

    </form>
</div>
@endsection
