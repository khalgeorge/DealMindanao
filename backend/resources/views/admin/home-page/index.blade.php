@extends('layouts.admin')

@section('title', 'Home Page Editor - DealMindanao Admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900">Home Page Editor</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Manage Every Section</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.settings.index') }}" class="btn-secondary btn-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Settings
        </a>
        <a href="{{ url('/') }}" target="_blank" class="btn-secondary btn-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            Preview
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── Sidebar Navigation ───────────────────────────────────────── --}}
        <div class="lg:col-span-1">
            <nav class="flex flex-col space-y-1 sticky top-24">
                @foreach([
                    ['meta',       'SEO / Meta',          'M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['hero',       'Hero Carousel',       'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['highlights', 'Weekly Highlights',   'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
                    ['benefits',   'Why Shop',            'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['steps',      'How It Works',        'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                    ['cta',        'Call to Action',      'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.952 9.168-4.938'],
                ] as [$id, $label, $icon])
                <button type="button" id="tab-btn-{{ $id }}" onclick="switchTab('{{ $id }}')"
                    class="tab-nav-btn flex items-center gap-3 px-4 py-3 text-sm font-bold rounded-lg text-gray-600 hover:bg-gray-100 transition-colors text-left w-full">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                    </svg>
                    {{ $label }}
                </button>
                @endforeach
            </nav>
        </div>

        {{-- ── Content Area ────────────────────────────────────────────── --}}
        <div class="lg:col-span-2 space-y-0">

            {{-- ──────────────────────────────────────────────────────────── --}}
            {{-- PANEL: SEO / META                                            --}}
            {{-- ──────────────────────────────────────────────────────────── --}}
            <div id="panel-meta" class="tab-panel hidden">
                <form action="{{ route('admin.home_page.meta.update') }}" method="POST">
                    @csrf
                    <div class="bg-white rounded-lg border border-gray-100 p-8 shadow-sm space-y-5">
                        <div>
                            <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">SEO / Meta</h2>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Browser tab title &amp; search engine description</p>
                        </div>
                        <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 border border-blue-100 rounded-lg">
                            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            <span class="text-xs font-bold text-blue-700 uppercase tracking-widest">Page URL:</span>
                            <a href="{{ url('/') }}" target="_blank" class="text-xs font-black text-blue-600 hover:underline">{{ url('/') }}</a>
                        </div>
                        <hr class="border-gray-100">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Title <span class="text-red-500">*</span> <span class="font-normal normal-case text-gray-400">(max 70 chars)</span></label>
                            <input type="text" name="home_meta_title" value="{{ old('home_meta_title', $s['home_meta_title'] ?? '') }}" class="input" maxlength="70" required>
                            <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 50–70 characters. Shown as the clickable headline in Google results.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Description <span class="font-normal normal-case text-gray-400">(max 300 chars)</span></label>
                            <textarea name="home_meta_description" rows="3" class="input resize-none" maxlength="300">{{ old('home_meta_description', $s['home_meta_description'] ?? '') }}</textarea>
                            <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 150–300 characters. Shown below the title in search results.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Keywords <span class="font-normal normal-case text-gray-400">(comma-separated)</span></label>
                            <input type="text" name="home_meta_keywords" value="{{ old('home_meta_keywords', $s['home_meta_keywords'] ?? '') }}" class="input" placeholder="hardware, heavy equipment, deals, Mindanao">
                            <p class="text-[10px] text-gray-400 mt-1 font-bold">Comma-separated list. Helps reinforce the page topic.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Canonical URL <span class="font-normal normal-case">(optional)</span></label>
                            <input type="text" name="home_canonical" value="{{ old('home_canonical', $s['home_canonical'] ?? '') }}" class="input" placeholder="{{ url('/') }}">
                            <p class="text-[10px] text-gray-400 mt-1 font-bold">Leave blank to use the default URL. Only set this if this page is accessible at multiple addresses.</p>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="btn-primary">Save SEO Settings</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ──────────────────────────────────────────────────────────── --}}
            {{-- PANEL: HERO CAROUSEL                                         --}}
            {{-- ──────────────────────────────────────────────────────────── --}}
            <div id="panel-hero" class="tab-panel hidden">
                <form action="{{ route('admin.home_page.hero.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white rounded-lg border border-gray-100 p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-1">
                            <div>
                                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Hero Carousel</h2>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Full-width banner with 2 rotating slides</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="home_hero_enabled" value="1" class="sr-only peer"
                                    {{ ($s['home_hero_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                                <span class="ml-2 text-sm font-bold text-gray-700">Enabled</span>
                            </label>
                        </div>
                        <hr class="border-gray-100 my-6">

                        <div class="space-y-8">
                        @foreach($s['home_hero_slides'] as $i => $slide)
                        <div class="p-6 bg-gray-50 rounded-lg border border-gray-100">
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-5">Slide {{ $i + 1 }}</p>

                            {{-- Image --}}
                            <div class="mb-5">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Hero Image</label>
                                @php
                                    $imgSrc = $slide['image'] ?? '';
                                    if ($imgSrc && !str_starts_with($imgSrc, '/') && !str_starts_with($imgSrc, 'http')) {
                                        $imgSrc = \Illuminate\Support\Facades\Storage::url($imgSrc);
                                    }
                                @endphp
                                @if($imgSrc)
                                <div class="mb-2 rounded-lg overflow-hidden w-full h-36 bg-gray-200">
                                    <img src="{{ $imgSrc }}" class="w-full h-full object-cover" id="slide-preview-{{ $i }}" alt="Slide {{ $i + 1 }}">
                                </div>
                                @endif
                                <input type="hidden" name="slide_{{ $i }}_image_current" value="{{ $slide['image'] ?? '' }}">
                                <input type="file" name="slide_{{ $i }}_image_upload" accept="image/*"
                                    onchange="previewImg(this,'slide-preview-{{ $i }}')">
                                <p class="text-xs text-gray-400 mt-1">Recommended: 1920×720px. Leave blank to keep current.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Heading <span class="text-red-500">*</span></label>
                                    <textarea name="slide_{{ $i }}_heading" rows="2" required>{{ $slide['heading'] ?? '' }}</textarea>
                                    <p class="text-xs text-gray-400 mt-1">Use a new line for a line break on display.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Highlighted Phrase</label>
                                    <input type="text" name="slide_{{ $i }}_heading_highlight" value="{{ $slide['heading_highlight'] ?? '' }}" placeholder="e.g. Hardware & Heavy Equipment">
                                    <p class="text-xs text-gray-400 mt-1">Exact phrase to render in color.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Highlight Color</label>
                                    <select name="slide_{{ $i }}_highlight_class">
                                        <option value="text-brand-400" {{ ($slide['highlight_class'] ?? '') === 'text-brand-400' ? 'selected' : '' }}>Green (Brand)</option>
                                        <option value="text-accent-400" {{ ($slide['highlight_class'] ?? '') === 'text-accent-400' ? 'selected' : '' }}>Amber (Accent)</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Subtext</label>
                                    <textarea name="slide_{{ $i }}_subtext" rows="2">{{ $slide['subtext'] ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">CTA Button Text</label>
                                    <input type="text" name="slide_{{ $i }}_cta_text" value="{{ $slide['cta_text'] ?? '' }}" placeholder="Shop Deals">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">CTA Button URL</label>
                                    <input type="text" name="slide_{{ $i }}_cta_url" value="{{ $slide['cta_url'] ?? '' }}" placeholder="/shop">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Category Pills</label>
                                    <input type="text" name="slide_{{ $i }}_pills" value="{{ $slide['pills'] ?? '' }}" placeholder="Power Tools, Home Improvement, Outdoor Gear">
                                    <p class="text-xs text-gray-400 mt-1">Comma-separated. Display as pill badges on the slide.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Trust Badge 1</label>
                                    <input type="text" name="slide_{{ $i }}_badge1" value="{{ $slide['badge1'] ?? '' }}" placeholder="Trusted Sellers">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Trust Badge 2</label>
                                    <input type="text" name="slide_{{ $i }}_badge2" value="{{ $slide['badge2'] ?? '' }}" placeholder="Cash on Delivery">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Disclaimer</label>
                                    <input type="text" name="slide_{{ $i }}_disclaimer" value="{{ $slide['disclaimer'] ?? '' }}" placeholder="Order request only...">
                                </div>
                            </div>
                        </div>
                        @endforeach
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="btn-primary">Save Hero Section</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ──────────────────────────────────────────────────────────── --}}
            {{-- PANEL: WEEKLY HIGHLIGHTS                                     --}}
            {{-- ──────────────────────────────────────────────────────────── --}}
            <div id="panel-highlights" class="tab-panel hidden">
                <form action="{{ route('admin.home_page.highlights.update') }}" method="POST">
                    @csrf
                    <div class="bg-white rounded-lg border border-gray-100 p-8 shadow-sm space-y-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Weekly Highlights</h2>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Section heading &amp; View All button for the featured products grid</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="home_highlights_enabled" value="1" class="sr-only peer"
                                    {{ ($s['home_highlights_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                                <span class="ml-2 text-sm font-bold text-gray-700">Enabled</span>
                            </label>
                        </div>
                        <hr class="border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Badge Label</label>
                                <input type="text" name="home_highlights_badge" value="{{ $s['home_highlights_badge'] ?? '' }}" placeholder="This Week">
                                <p class="text-xs text-gray-400 mt-1">Small pill shown above the heading.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Section Heading <span class="text-red-500">*</span></label>
                                <input type="text" name="home_highlights_heading" value="{{ $s['home_highlights_heading'] ?? '' }}" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Section Description</label>
                                <textarea name="home_highlights_subtext" rows="2">{{ $s['home_highlights_subtext'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">"View All" Button Text</label>
                                <input type="text" name="home_highlights_cta_text" value="{{ $s['home_highlights_cta_text'] ?? '' }}" placeholder="View All Products">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">"View All" Button URL</label>
                                <input type="text" name="home_highlights_cta_url" value="{{ $s['home_highlights_cta_url'] ?? '' }}" placeholder="/shop">
                            </div>
                        </div>
                        <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg">
                            <p class="text-xs text-blue-700 font-bold"><strong>Note:</strong> Products displayed here are pulled automatically from items marked as "Featured" in the Products section.</p>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="btn-primary">Save Weekly Highlights</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ──────────────────────────────────────────────────────────── --}}
            {{-- PANEL: WHY SHOP (BENEFITS)                                   --}}
            {{-- ──────────────────────────────────────────────────────────── --}}
            <div id="panel-benefits" class="tab-panel hidden">
                <form action="{{ route('admin.home_page.benefits.update') }}" method="POST">
                    @csrf
                    <div class="bg-white rounded-lg border border-gray-100 p-8 shadow-sm space-y-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Why Shop</h2>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">4-column benefit cards section</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="home_benefits_enabled" value="1" class="sr-only peer"
                                    {{ ($s['home_benefits_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                                <span class="ml-2 text-sm font-bold text-gray-700">Enabled</span>
                            </label>
                        </div>
                        <hr class="border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Section Heading <span class="text-red-500">*</span></label>
                                <input type="text" name="home_benefits_heading" value="{{ $s['home_benefits_heading'] ?? '' }}" required>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Section Subtext</label>
                                <input type="text" name="home_benefits_subtext" value="{{ $s['home_benefits_subtext'] ?? '' }}">
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-5">
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Benefit Cards</p>
                            <div class="space-y-3">
                                @foreach($s['home_benefits_items'] as $bi => $benefit)
                                <div class="p-5 bg-gray-50 rounded-lg border border-gray-100">
                                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Card {{ $bi + 1 }}</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Title <span class="text-red-500">*</span></label>
                                            <input type="text" name="benefit_title[]" value="{{ $benefit['title'] ?? '' }}" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Description <span class="text-red-500">*</span></label>
                                            <input type="text" name="benefit_description[]" value="{{ $benefit['description'] ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn-primary">Save Why Shop Section</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ──────────────────────────────────────────────────────────── --}}
            {{-- PANEL: HOW IT WORKS (STEPS)                                  --}}
            {{-- ──────────────────────────────────────────────────────────── --}}
            <div id="panel-steps" class="tab-panel hidden">
                <form action="{{ route('admin.home_page.steps.update') }}" method="POST">
                    @csrf
                    <div class="bg-white rounded-lg border border-gray-100 p-8 shadow-sm space-y-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">How It Works</h2>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Timeline showing the order process steps</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="home_steps_enabled" value="1" class="sr-only peer"
                                    {{ ($s['home_steps_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                                <span class="ml-2 text-sm font-bold text-gray-700">Enabled</span>
                            </label>
                        </div>
                        <hr class="border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Section Heading <span class="text-red-500">*</span></label>
                                <input type="text" name="home_steps_heading" value="{{ $s['home_steps_heading'] ?? '' }}" required>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Section Subtext</label>
                                <input type="text" name="home_steps_subtext" value="{{ $s['home_steps_subtext'] ?? '' }}">
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-5">
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Timeline Steps</p>
                            <div class="space-y-3">
                                @foreach($s['home_steps_items'] as $si => $step)
                                <div class="p-5 bg-gray-50 rounded-lg border border-gray-100">
                                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Step {{ $si + 1 }}</p>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Title <span class="text-red-500">*</span></label>
                                            <input type="text" name="step_title[]" value="{{ $step['title'] ?? '' }}" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Description <span class="text-red-500">*</span></label>
                                            <input type="text" name="step_description[]" value="{{ $step['description'] ?? '' }}" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Time Label</label>
                                            <input type="text" name="step_time_label[]" value="{{ $step['time_label'] ?? '' }}" placeholder="e.g. Within 24 hours">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-5">
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Info / Help Box</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Box Heading</label>
                                    <input type="text" name="home_steps_info_heading" value="{{ $s['home_steps_info_heading'] ?? '' }}">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Box Text</label>
                                    <textarea name="home_steps_info_text" rows="2">{{ $s['home_steps_info_text'] ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link 1 Text</label>
                                    <input type="text" name="home_steps_info_link1_text" value="{{ $s['home_steps_info_link1_text'] ?? '' }}" placeholder="Visit Help Center">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link 1 URL</label>
                                    <input type="text" name="home_steps_info_link1_url" value="{{ $s['home_steps_info_link1_url'] ?? '' }}" placeholder="/help">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link 2 Text</label>
                                    <input type="text" name="home_steps_info_link2_text" value="{{ $s['home_steps_info_link2_text'] ?? '' }}" placeholder="Contact Support">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link 2 URL</label>
                                    <input type="text" name="home_steps_info_link2_url" value="{{ $s['home_steps_info_link2_url'] ?? '' }}" placeholder="/contact">
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="btn-primary">Save How It Works</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ──────────────────────────────────────────────────────────── --}}
            {{-- PANEL: CALL TO ACTION                                        --}}
            {{-- ──────────────────────────────────────────────────────────── --}}
            <div id="panel-cta" class="tab-panel hidden">
                <form action="{{ route('admin.home_page.cta.update') }}" method="POST">
                    @csrf
                    <div class="bg-white rounded-lg border border-gray-100 p-8 shadow-sm space-y-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-black text-gray-900 uppercase tracking-tight">Call to Action</h2>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Green banner at the bottom with two action buttons</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="home_cta_enabled" value="1" class="sr-only peer"
                                    {{ ($s['home_cta_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-600"></div>
                                <span class="ml-2 text-sm font-bold text-gray-700">Enabled</span>
                            </label>
                        </div>
                        <hr class="border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Heading <span class="text-red-500">*</span></label>
                                <input type="text" name="home_cta_heading" value="{{ $s['home_cta_heading'] ?? '' }}" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Subtext</label>
                                <input type="text" name="home_cta_subtext" value="{{ $s['home_cta_subtext'] ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Button 1 Text</label>
                                <input type="text" name="home_cta_btn1_text" value="{{ $s['home_cta_btn1_text'] ?? '' }}" placeholder="Become a Partner">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Button 1 URL</label>
                                <input type="text" name="home_cta_btn1_url" value="{{ $s['home_cta_btn1_url'] ?? '' }}" placeholder="/partner">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Button 2 Text</label>
                                <input type="text" name="home_cta_btn2_text" value="{{ $s['home_cta_btn2_text'] ?? '' }}" placeholder="Learn More">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Button 2 URL</label>
                                <input type="text" name="home_cta_btn2_url" value="{{ $s['home_cta_btn2_url'] ?? '' }}" placeholder="/about">
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="btn-primary">Save Call to Action</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>{{-- end content area --}}
    </div>{{-- end grid --}}
</div>{{-- end admin-content --}}

@endsection

@push('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.tab-nav-btn').forEach(b => {
        b.classList.remove('bg-white', 'border', 'border-gray-100', 'text-brand-600', 'shadow-sm');
        b.classList.add('text-gray-600');
    });
    const panel = document.getElementById('panel-' + tab);
    const btn   = document.getElementById('tab-btn-' + tab);
    if (panel) panel.classList.remove('hidden');
    if (btn) {
        btn.classList.remove('text-gray-600');
        btn.classList.add('bg-white', 'border', 'border-gray-100', 'text-brand-600', 'shadow-sm');
    }
    sessionStorage.setItem('homePageTab', tab);
}

function previewImg(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            let img = document.getElementById(previewId);
            if (!img) {
                img = document.createElement('img');
                img.id = previewId;
                img.className = 'w-full h-full object-cover';
                const wrap = document.createElement('div');
                wrap.className = 'mb-2 rounded-lg overflow-hidden w-full h-36 bg-gray-200';
                wrap.appendChild(img);
                input.parentElement.insertBefore(wrap, input);
            }
            img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const savedTab = '{{ session('tab') }}' || sessionStorage.getItem('homePageTab') || 'meta';
    switchTab(savedTab);
});
</script>
@endpush
