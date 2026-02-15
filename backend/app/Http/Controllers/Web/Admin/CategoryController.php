<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        $categories = $query->latest()->paginate(15);
        
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }
    
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }
    
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing products!');
        }
        
        $category->delete();
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
    
    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        
        return back()->with('success', 'Category status updated!');
    }
}
