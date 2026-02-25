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
        
        // Get all categories for client-side pagination
        $allCategories = $query->latest()->get();

        // $perPage must be at least 1 — LengthAwarePaginator divides by it
        // and throws DivisionByZeroError when the collection is empty.
        $perPage = max(1, $allCategories->count());

        // Create a mock paginator for Blade compatibility
        $categories = new \Illuminate\Pagination\LengthAwarePaginator(
            $allCategories,
            $allCategories->count(),
            $perPage,
            1,
            ['path' => $request->url()]
        );
        
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
                'category' => $category
            ]);
        }
        
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
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully!',
                'category' => $category
            ]);
        }
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }
    
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with existing products!'
                ], 422);
            }
            return back()->with('error', 'Cannot delete category with existing products!');
        }
        
        $category->delete();
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!'
            ]);
        }
        
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
