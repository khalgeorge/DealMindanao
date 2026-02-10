<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('slug')->paginate(10);

        return view('admin.pages.index', [
            'pages' => $pages,
        ]);
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'subtitle' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:4096',
        ]);

        $data['slug'] = Str::slug($data['slug']);
        if ($data['slug'] === '') {
            return back()->withErrors(['slug' => 'Slug is invalid.'])->withInput();
        }
        if (Page::where('slug', $data['slug'])->exists()) {
            return back()->withErrors(['slug' => 'Slug already exists.'])->withInput();
        }

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('pages', 'public');
        }

        if ($request->hasFile('hero_image')) {
            $data['hero_image_path'] = $request->file('hero_image')->store('pages', 'public');
        }

        Page::create($data);

        return redirect('/admin/pages')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', [
            'page' => $page,
        ]);
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'slug' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'subtitle' => 'nullable|string|max:255',
            'body' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:4096',
        ]);

        $data['slug'] = Str::slug($data['slug']);
        if ($data['slug'] === '') {
            return back()->withErrors(['slug' => 'Slug is invalid.'])->withInput();
        }
        if (Page::where('slug', $data['slug'])->where('id', '!=', $page->id)->exists()) {
            return back()->withErrors(['slug' => 'Slug already exists.'])->withInput();
        }

        if ($request->hasFile('logo')) {
            if ($page->logo_path) {
                Storage::disk('public')->delete($page->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('pages', 'public');
        }

        if ($request->hasFile('hero_image')) {
            if ($page->hero_image_path) {
                Storage::disk('public')->delete($page->hero_image_path);
            }
            $data['hero_image_path'] = $request->file('hero_image')->store('pages', 'public');
        }

        $page->update($data);

        return redirect('/admin/pages')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        if ($page->logo_path) {
            Storage::disk('public')->delete($page->logo_path);
        }
        if ($page->hero_image_path) {
            Storage::disk('public')->delete($page->hero_image_path);
        }

        $page->delete();

        return redirect('/admin/pages')->with('success', 'Page deleted successfully.');
    }
}
