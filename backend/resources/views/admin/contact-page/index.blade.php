@extends('layouts.admin')

@section('title', 'Contact Page Editor - DealMindanao Admin')

@section('content')
<header class="admin-header">
    <div>
        <h1 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Contact Page Editor</h1>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">All content on /contact</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.settings.index') }}" class="btn-secondary btn-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Settings
        </a>
        <a href="{{ url('/contact') }}" target="_blank" class="btn-secondary btn-sm flex items-center gap-2">
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

    <form action="{{ route('admin.contact_page.update') }}" method="POST" class="space-y-8">
        @csrf

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 1 — HERO / INTRO
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14l4 4V5c0-1.1-.9-2-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Intro / Headline</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Badge · heading · description paragraph</p>
                </div>
            </div>
            <div class="p-8 space-y-5">
                {{-- Badge --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Badge Label</label>
                    <input type="text" name="contact_badge"
                           value="{{ old('contact_badge', $s['contact_badge']) }}"
                           class="input" placeholder="e.g. Contact Support">
                    <p class="text-xs text-gray-400 mt-1">Small pill shown above the heading. Leave blank to hide.</p>
                    @error('contact_badge')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                {{-- Heading --}}
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                            Heading — Plain Part <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="contact_title"
                               value="{{ old('contact_title', $s['contact_title']) }}"
                               class="input" required placeholder="e.g. We're here to">
                        @error('contact_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">
                            Heading — Highlight Part <span class="text-red-500">*</span>
                            <span class="normal-case font-medium text-gray-300 ml-1">(brand green)</span>
                        </label>
                        <input type="text" name="contact_title_highlight"
                               value="{{ old('contact_title_highlight', $s['contact_title_highlight']) }}"
                               class="input" required placeholder="e.g. help">
                        @error('contact_title_highlight')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                {{-- Heading preview --}}
                <div class="px-4 py-3 bg-gray-50 rounded-lg text-lg font-black">
                    <span class="text-gray-900">{{ $s['contact_title'] }}</span>
                    <span class="text-brand-600"> {{ $s['contact_title_highlight'] }}</span>
                </div>
                {{-- Description --}}
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Description <span class="text-red-500">*</span></label>
                    <textarea name="contact_description" rows="3" class="textarea" required
                              placeholder="Introductory paragraph...">{{ old('contact_description', $s['contact_description']) }}</textarea>
                    @error('contact_description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 2 — CONTACT INFO
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-50 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-tight">Contact Information</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Email address · office location</p>
                </div>
            </div>
            <div class="p-8 grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_email"
                           value="{{ old('contact_email', $s['contact_email']) }}"
                           class="input" required placeholder="hello@dealmindanao.ph">
                    @error('contact_email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Office Address <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_address"
                           value="{{ old('contact_address', $s['contact_address']) }}"
                           class="input" required placeholder="Poblacion District, Davao City, 8000">
                    @error('contact_address')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             INFO — CONTACT FORM
        ══════════════════════════════════════════════════════════════ --}}
        <div class="p-5 bg-gray-50 border border-gray-100 rounded-lg flex items-start gap-3">
            <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-xs text-gray-500">
                The <strong>contact form</strong> (name, subject, message, send button) is part of the page layout and does not have editable fields here. To enable form submissions, a backend handler route and mail configuration are required.
            </p>
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
                    <a href="{{ url('/contact') }}" target="_blank" class="text-xs font-black text-blue-600 hover:underline">{{ url('/contact') }}</a>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Title <span class="font-normal normal-case">(max 70 chars)</span></label>
                    <input type="text" name="contact_meta_title" value="{{ old('contact_meta_title', $s['contact_meta_title']) }}" class="input" placeholder="Contact Us - DealMindanao" maxlength="70">
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 50–70 characters.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Meta Description <span class="font-normal normal-case">(max 160 chars)</span></label>
                    <textarea name="contact_meta_description" rows="3" class="input resize-none" placeholder="Brief description for search engines…" maxlength="160">{{ old('contact_meta_description', $s['contact_meta_description']) }}</textarea>
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Recommended: 150–160 characters.</p>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Keywords <span class="font-normal normal-case">(comma-separated)</span></label>
                    <input type="text" name="contact_meta_keywords" value="{{ old('contact_meta_keywords', $s['contact_meta_keywords']) }}" class="input" placeholder="contact, support, DealMindanao">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Canonical URL <span class="font-normal normal-case">(optional)</span></label>
                    <input type="text" name="contact_canonical" value="{{ old('contact_canonical', $s['contact_canonical']) }}" class="input" placeholder="{{ url('/contact') }}">
                    <p class="text-[10px] text-gray-400 mt-1 font-bold">Leave blank to use the default URL. Only set this if this page is accessible at multiple addresses.</p>
                </div>
            </div>
        </div>

        {{-- SAVE BAR --}}
        <div class="flex items-center gap-4 pt-2 pb-8">
            <button type="submit" class="btn-primary btn-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Changes
            </button>
            <a href="{{ url('/contact') }}" target="_blank" class="btn-secondary btn-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                View Live Page
            </a>
        </div>

    </form>
</div>
@endsection
