<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);

        if ($data['slug'] === '') {
            return back()->withErrors(['slug' => 'Slug is invalid.'])->withInput();
        }

        if (Category::where('slug', $data['slug'])->exists()) {
            return back()->withErrors(['slug' => 'Slug already exists.'])->withInput();
        }

        Category::create($data);

        return redirect('/admin/categories')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($data['slug'] ?: $data['name']);

        if ($data['slug'] === '') {
            return back()->withErrors(['slug' => 'Slug is invalid.'])->withInput();
        }

        if (Category::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists()) {
            return back()->withErrors(['slug' => 'Slug already exists.'])->withInput();
        }

        $category->update($data);

        return redirect('/admin/categories')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect('/admin/categories')->with('error', 'Cannot delete a category that has products.');
        }

        $category->delete();

        return redirect('/admin/categories')->with('success', 'Category deleted successfully.');
    }
}
