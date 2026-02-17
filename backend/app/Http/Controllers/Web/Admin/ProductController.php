<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'company']);
        
        // Capture filter values
        $search = $request->get('search', '');
        $categoryId = $request->get('category', '');
        $companyId = $request->get('company', '');
        $status = $request->get('status', '');
        $sort = $request->get('sort', 'latest');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category_id', $categoryId);
        }
        
        if ($request->filled('company')) {
            $query->where('company_id', $companyId);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $status === 'active');
        }
        
        // Apply sorting
        switch ($sort) {
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }
        
        $products = $query->paginate(15);
        $categories = Category::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        
        return view('admin.products.index', compact(
            'products', 
            'categories', 
            'companies',
            'search',
            'categoryId',
            'companyId',
            'status',
            'sort'
        ));
    }
    
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.products.create', compact('categories', 'companies'));
    }
    
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        
        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
            
            // Ensure uniqueness
            $originalSlug = $data['slug'];
            $counter = 1;
            while (\App\Models\Product::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        
        // Handle images - merge uploaded files with URL entries
        $images = [];
        
        // Get uploaded image paths from hidden inputs
        if ($request->has('uploaded_images')) {
            $uploadedImages = array_filter(explode(',', $request->uploaded_images));
            $images = array_merge($images, $uploadedImages);
        }
        
        // Get URL images
        if ($request->has('images') && is_array($request->images)) {
            $urlImages = array_filter($request->images);
            $images = array_merge($images, $urlImages);
        }
        
        $data['images'] = array_values(array_unique($images));
        $data['is_active'] = $data['is_active'] ?? true;
        $data['is_featured'] = $data['is_featured'] ?? false;
        
        $product = Product::create($data);
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }
    
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'companies'));
    }
    
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        
        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
            
            // Ensure uniqueness (excluding current product)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (\App\Models\Product::where('slug', $data['slug'])->where('id', '!=', $product->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        
        // Handle images - merge uploaded files with URL entries
        $images = [];
        
        // Get uploaded image paths from hidden inputs
        if ($request->has('uploaded_images')) {
            $uploadedImages = array_filter(explode(',', $request->uploaded_images));
            $images = array_merge($images, $uploadedImages);
        }
        
        // Get URL images
        if ($request->has('images') && is_array($request->images)) {
            $urlImages = array_filter($request->images);
            $images = array_merge($images, $urlImages);
        }
        
        $data['images'] = array_values(array_unique($images));
        
        $product->update($data);
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }
    
    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
    
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        
        return back()->with('success', 'Product status updated!');
    }
    
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        
        return back()->with('success', 'Featured status updated!');
    }
    
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:10240'], // 10MB
            ], [
                'image.required' => 'Please select an image file',
                'image.image' => 'The file must be an image',
                'image.mimes' => 'Only JPEG, PNG, WebP, and GIF images are allowed',
                'image.max' => 'Image size must not exceed 10MB',
            ]);
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                
                // Check if file upload was successful
                if (!$image->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'File upload failed: ' . $image->getErrorMessage()
                    ], 400);
                }
                
                $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('products', $filename, 'public');
                
                return response()->json([
                    'success' => true,
                    'path' => '/storage/' . $path,
                    'filename' => $filename
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No image uploaded'
            ], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = array_values($errors)[0][0] ?? 'Validation failed';
            
            return response()->json([
                'success' => false,
                'message' => $firstError,
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Image upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
