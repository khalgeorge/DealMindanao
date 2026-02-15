<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();
        
        return response()->json($categories);
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        $category->loadCount('products');
        
        return response()->json($category);
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        // Auto-generate slug from name
        $slug = \Illuminate\Support\Str::slug($validated['name']);
        
        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        // If name is being updated, regenerate slug
        if (isset($validated['name']) && $validated['name'] !== $category->name) {
            $slug = \Illuminate\Support\Str::slug($validated['name']);
            
            // Ensure slug is unique (excluding current category)
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $validated['slug'] = $slug;
        }

        $category->update($validated);

        return response()->json($category);
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with existing products',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ]);
    }
}
