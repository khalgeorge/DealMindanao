@extends('layouts.admin')

@section('title', 'About Page Editor - DealMindanao Admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900 uppercase tracking-tighter">About Page Editor</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">All content on /about</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.settings.index') }}" class="btn-secondary btn-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Settings
        </a>
        <a href="{{ url('/about') }}" target="_blank" class="btn-secondary btn-sm flex items-center gap-2">
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

    <form action="{{ route('admin.about_page.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 1 — HERO
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Hero Section</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Top banner title & description</p>
                </div>
            </div>
            <div class="p-8 space-y-5">
                {{-- Title --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                            Title <span class="text-red-500">*</span>
                            <span class="normal-case font-medium text-gray-300 ml-1">(plain text)</span>
                        </label>
                        <input type="text" name="about_hero_title"
                               value="{{ old('about_hero_title', $s['about_hero_title']) }}"
                               class="input" required
                               placeholder="e.g. About">
                        @error('about_hero_title')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                            Title Highlight <span class="text-red-500">*</span>
                            <span class="normal-case font-medium text-gray-300 ml-1">(shown in green)</span>
                        </label>
                        <input type="text" name="about_hero_title_highlight"
                               value="{{ old('about_hero_title_highlight', $s['about_hero_title_highlight']) }}"
                               class="input" required
                               placeholder="e.g. DealMindanao">
                        @error('about_hero_title_highlight')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                {{-- Preview --}}
                <div class="px-4 py-3 bg-brand-50 rounded-lg text-sm text-gray-500 font-medium">
                    Preview: <strong class="text-gray-900">{{ $s['about_hero_title'] }}</strong>
                    <strong class="text-brand-600"> {{ $s['about_hero_title_highlight'] }}</strong>
                </div>
                {{-- Subtitle --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                        Description Paragraph <span class="text-red-500">*</span>
                    </label>
                    <textarea name="about_hero_subtitle" rows="4" class="textarea" required
                              placeholder="Hero description paragraph...">{{ old('about_hero_subtitle', $s['about_hero_subtitle']) }}</textarea>
                    @error('about_hero_subtitle')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 2 — VALUE CARDS
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Value Cards</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">3 feature cards · Card 2 is the featured (green) card</p>
                </div>
            </div>
            <div class="p-8 space-y-6">

                @foreach([
                    ['1', 'Card 1', 'White card (left)', '01'],
                    ['2', 'Card 2', 'Featured green card (center)', '02'],
                    ['3', 'Card 3', 'White card (right)', '03'],
                ] as [$n, $label, $note, $num])
                <div class="p-6 rounded-lg border {{ $n === '2' ? 'border-brand-200 bg-brand-50' : 'border-gray-100 bg-gray-50' }}">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 {{ $n === '2' ? 'bg-brand-600 text-white' : 'bg-white text-gray-500 border border-gray-200' }} rounded-lg flex items-center justify-center text-xs font-black">{{ $num }}</div>
                        <div>
                            <p class="text-sm font-black text-gray-900">{{ $label }}</p>
                            <p class="text-xs text-gray-400 font-medium">{{ $note }}</p>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="about_card{{ $n }}_title"
                                   value="{{ old('about_card'.$n.'_title', $s['about_card'.$n.'_title']) }}"
                                   class="input" required
                                   placeholder="Card title">
                            @error('about_card'.$n.'_title')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Description <span class="text-red-500">*</span></label>
                            <textarea name="about_card{{ $n }}_description" rows="3" class="textarea" required
                                      placeholder="Card description...">{{ old('about_card'.$n.'_description', $s['about_card'.$n.'_description']) }}</textarea>
                            @error('about_card'.$n.'_description')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 3 — STORY SECTION
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Story Section</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Two-column section with image, text &amp; buttons</p>
                </div>
            </div>
            <div class="p-8 space-y-6">

                {{-- Section Heading --}}
                <div>
                    <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Section Heading</h3>
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                                Main Text <span class="text-red-500">*</span>
                                <span class="normal-case font-medium text-gray-300 ml-1">(plain)</span>
                            </label>
                            <input type="text" name="about_section_title"
                                   value="{{ old('about_section_title', $s['about_section_title']) }}"
                                   class="input" required
                                   placeholder="e.g. Authenticity in every">
                            @error('about_section_title')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                                Highlight Word <span class="text-red-500">*</span>
                                <span class="normal-case font-medium text-gray-300 ml-1">(italic green)</span>
                            </label>
                            <input type="text" name="about_section_title_highlight"
                                   value="{{ old('about_section_title_highlight', $s['about_section_title_highlight']) }}"
                                   class="input" required
                                   placeholder="e.g. Package.">
                            @error('about_section_title_highlight')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3 px-4 py-3 bg-gray-50 rounded-lg text-sm font-medium text-gray-500">
                        Preview: <strong class="text-gray-900">{{ $s['about_section_title'] }}</strong>
                        <em class="text-brand-600"> {{ $s['about_section_title_highlight'] }}</em>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Story Paragraphs --}}
                <div>
                    <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Story Text</h3>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Paragraph 1 <span class="text-red-500">*</span></label>
                            <textarea name="about_story_paragraph1" rows="4" class="textarea" required
                                      placeholder="First paragraph...">{{ old('about_story_paragraph1', $s['about_story_paragraph1']) }}</textarea>
                            @error('about_story_paragraph1')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Paragraph 2 <span class="text-red-500">*</span></label>
                            <textarea name="about_story_paragraph2" rows="4" class="textarea" required
                                      placeholder="Second paragraph...">{{ old('about_story_paragraph2', $s['about_story_paragraph2']) }}</textarea>
                            @error('about_story_paragraph2')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- CTA Buttons --}}
                <div>
                    <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">CTA Buttons</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Button 1 --}}
                        <div class="p-5 bg-gray-50 rounded-lg border border-gray-100 space-y-3">
                            <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Primary Button</p>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Label <span class="text-red-500">*</span></label>
                                <input type="text" name="about_cta1_label"
                                       value="{{ old('about_cta1_label', $s['about_cta1_label']) }}"
                                       class="input" required placeholder="e.g. Shop Deals">
                                @error('about_cta1_label')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link URL <span class="text-red-500">*</span></label>
                                <input type="text" name="about_cta1_link"
                                       value="{{ old('about_cta1_link', $s['about_cta1_link']) }}"
                                       class="input" required placeholder="e.g. /shop">
                                @error('about_cta1_link')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        {{-- Button 2 --}}
                        <div class="p-5 bg-gray-50 rounded-lg border border-gray-100 space-y-3">
                            <p class="text-xs font-black text-gray-500 uppercase tracking-widest">Secondary Button</p>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Label <span class="text-red-500">*</span></label>
                                <input type="text" name="about_cta2_label"
                                       value="{{ old('about_cta2_label', $s['about_cta2_label']) }}"
                                       class="input" required placeholder="e.g. Become a Partner">
                                @error('about_cta2_label')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Link URL <span class="text-red-500">*</span></label>
                                <input type="text" name="about_cta2_link"
                                       value="{{ old('about_cta2_link', $s['about_cta2_link']) }}"
                                       class="input" required placeholder="e.g. /partner">
                                @error('about_cta2_link')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Image --}}
                <div>
                    <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Story Image</h3>

                    {{-- Current image preview --}}
                    @php
                        $imgSrc = $s['about_image'];
                        if ($imgSrc && !str_starts_with($imgSrc, '/') && !str_starts_with($imgSrc, 'http')) {
                            $imgSrc = \Illuminate\Support\Facades\Storage::url($imgSrc);
                        }
                    @endphp
                    @if($imgSrc)
                    <div class="mb-4 relative rounded-lg overflow-hidden border border-gray-100 aspect-video max-w-sm bg-gray-50">
                        <img src="{{ $imgSrc }}" alt="Story image" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/0"></div>
                        <p class="absolute bottom-2 left-2 text-[10px] font-bold text-white bg-black/50 px-2 py-1 rounded">Current image</p>
                    </div>
                    @endif

                    {{-- Hidden field to preserve existing value --}}
                    <input type="hidden" name="about_image_current" value="{{ $s['about_image'] }}">

                    <div class="grid md:grid-cols-2 gap-5">
                        {{-- Upload --}}
                        <div class="p-5 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Upload New Image</label>
                            <div class="relative">
                                <input type="file" id="about-image-upload" name="about_image_upload"
                                       class="hidden" accept="image/*"
                                       onchange="previewImage(this)">
                                <label for="about-image-upload"
                                       class="flex items-center justify-center gap-2 w-full py-3 px-4 bg-white border border-gray-200 rounded-lg text-xs font-black text-gray-600 hover:bg-gray-100 cursor-pointer transition-colors uppercase tracking-widest">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Choose File
                                </label>
                                <p id="upload-name" class="text-[11px] text-brand-600 font-bold mt-2 text-center hidden"></p>
                            </div>
                            <p class="text-[11px] text-gray-400 font-medium mt-2">Max 5 MB · JPG, PNG, WebP</p>
                        </div>
                        {{-- URL --}}
                        <div class="p-5 bg-gray-50 rounded-lg border border-gray-100">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Or Use Image URL</label>
                            <input type="url" name="about_image_url"
                                   class="input"
                                   placeholder="https://example.com/image.jpg">
                            <p class="text-[11px] text-gray-400 font-medium mt-2">Upload takes priority over URL if both are provided.</p>
                        </div>
                    </div>
                    @error('about_image_upload')
                        <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                    @enderror
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
                    <a href="{{ url('/about') }}" target="_blank" class="text-xs font-black text-blue-600 hover:underline">{{ url('/about') }}</a>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Title <span class="font-normal normal-case">(max 160 chars)</span></label>
                    <input type="text" name="about_meta_title" value="{{ old('about_meta_title', $s['about_meta_title']) }}" class="input" placeholder="About DealMindanao - Hardware &amp; Heavy Equipment" maxlength="160">
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 50–160 characters. Shown as the clickable headline in Google results.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Description <span class="font-normal normal-case">(max 160 chars)</span></label>
                    <textarea name="about_meta_description" rows="3" class="input resize-none" placeholder="Brief description for search engines…" maxlength="160">{{ old('about_meta_description', $s['about_meta_description']) }}</textarea>
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 150–160 characters. Shown below the title in Google results.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Keywords <span class="font-normal normal-case">(comma-separated)</span></label>
                    <input type="text" name="about_meta_keywords" value="{{ old('about_meta_keywords', $s['about_meta_keywords']) }}" class="input" placeholder="hardware, heavy equipment, Mindanao">
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Comma-separated list. Helps reinforce the page topic.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Canonical URL <span class="font-normal normal-case">(optional)</span></label>
                    <input type="text" name="about_canonical" value="{{ old('about_canonical', $s['about_canonical']) }}" class="input" placeholder="{{ url('/about') }}">
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
            <a href="{{ url('/about') }}" target="_blank" class="btn-secondary btn-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                View Live Page
            </a>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const name = document.getElementById('upload-name');
    if (input.files && input.files[0]) {
        name.textContent = input.files[0].name;
        name.classList.remove('hidden');
    }
}
</script>
@endpush
