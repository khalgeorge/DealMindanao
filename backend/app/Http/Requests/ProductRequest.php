<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $productId = $this->route('product');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug')->ignore($productId),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:999999'],
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['string', 'max:500'],
            
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'company_id' => 'company',
            'stock_quantity' => 'stock',
            'is_active' => 'active status',
            'is_featured' => 'featured status',
            'meta_title' => 'SEO title',
            'meta_description' => 'SEO description',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'slug.regex' => 'The slug must be lowercase and contain only letters, numbers, and hyphens.',
            'images.max' => 'You can upload a maximum of 10 images per product.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-generate slug from name if not provided
        if (!$this->slug && $this->name) {
            $this->merge([
                'slug' => str($this->name)->slug()->toString(),
            ]);
        }

        // Convert checkboxes to boolean
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}
