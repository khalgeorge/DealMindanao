<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->is_admin;
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
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug')->ignore($productId),
            ],
            'description' => ['nullable', 'string', 'max:5000'],

            // Product identification
            'brand_id'   => ['nullable', 'integer', 'exists:brands,id'],
            'model_code' => [
                'nullable',
                'string',
                'max:255',
                // Unique within the same supplier
                Rule::unique('products', 'model_code')
                    ->where(fn ($q) => $q->where('supplier_id', $this->input('supplier_id')))
                    ->ignore($productId),
            ],
            'variant' => ['nullable', 'string', 'max:255'],

            // Pricing
            'supplier_price' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'srp'            => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            // Legacy price column — kept in sync via prepareForValidation
            'price'          => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'discount'       => ['nullable', 'numeric', 'min:0', 'max:99999999.99', 'lt:price'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:999999'],
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($productId),
            ],
            
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            
            'images' => [
                // Require at least one image when explicitly publishing a product.
                function ($attribute, $value, $fail) {
                    if ($this->input('status') === 'published') {
                        $uploaded = array_filter(explode(',', (string) $this->input('uploaded_images', '')));
                        if (empty($uploaded)) {
                            $fail('A product must have at least one image before it can be published.');
                        }
                    }
                },
                'nullable', 'array', 'max:10',
            ],
            // images.* is intentionally omitted — images are uploaded via AJAX
            // to /admin/products/upload-image and paths stored in uploaded_images.
            'uploaded_images' => ['nullable', 'string'],

            'status' => ['nullable', Rule::in(['draft', 'published'])],

            'promo_label'     => ['nullable', 'string', 'max:60'],
            'promo_starts_at' => ['nullable', 'date'],
            'promo_ends_at'   => ['nullable', 'date', function ($attribute, $value, $fail) {
                $startsAt = $this->input('promo_starts_at');
                if ($value && $startsAt && strtotime($value) <= strtotime($startsAt)) {
                    $fail('The promo end date must be after the start date.');
                }
            }],

            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'specifications' => ['nullable', 'array'],
            'variants'       => ['nullable', 'array'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id'    => 'category',
            'supplier_id'    => 'supplier',
            'stock_quantity' => 'stock',
            'is_active'      => 'active status',
            'is_featured'    => 'featured status',
            'meta_title'     => 'SEO title',
            'meta_description' => 'SEO description',
            'promo_label'    => 'promo label',
            'promo_starts_at' => 'promo start date',
            'promo_ends_at'  => 'promo end date',
            'status'         => 'publish status',
            'supplier_price' => 'supplier / cost price',
            'srp'            => 'selling price (SRP)',
            'brand'          => 'brand',
            'model_code'     => 'model code',
            'variant'        => 'variant',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'slug.regex'           => 'The slug must be lowercase and contain only letters, numbers, and hyphens.',
            'images.max'           => 'You can upload a maximum of 10 images per product.',
            'discount.lt'          => 'The discount must be less than the product price.',
            'status.in'            => "Product status must be either 'draft' or 'published'.",
            'supplier_price.required' => 'A supplier / cost price is required.',
            'supplier_price.min'   => 'The supplier price must be greater than zero.',
            'model_code.unique'    => 'This model code is already registered for the selected partner.',
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

        // Auto-compute SRP from supplier_price when the admin leaves it blank
        $supplierPrice = (float) $this->input('supplier_price', 0);
        if ($supplierPrice > 0 && !$this->filled('srp')) {
            $margin = (float) config('products.default_margin', 0.25);
            $this->merge([
                'srp' => round($supplierPrice * (1 + $margin), 2),
            ]);
        }

        // Keep the legacy price column in sync with srp
        if ($this->filled('srp')) {
            $this->merge(['price' => $this->input('srp')]);
        }

        // Nullify empty promo fields so they pass nullable date validation
        $this->merge([
            'promo_label'     => $this->promo_label     ?: null,
            'promo_starts_at' => $this->promo_starts_at ?: null,
            'promo_ends_at'   => $this->promo_ends_at   ?: null,
        ]);

        // Convert checkboxes to boolean
        $this->merge([
            'is_active'   => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);

        // Decode specifications JSON string submitted from the form builder
        if ($this->filled('specifications') && is_string($this->input('specifications'))) {
            $decoded = json_decode($this->input('specifications'), true);
            $this->merge(['specifications' => is_array($decoded) ? $decoded : null]);
        }

        // Decode variants JSON string submitted from the form builder
        if ($this->filled('variants') && is_string($this->input('variants'))) {
            $decoded = json_decode($this->input('variants'), true);
            $this->merge(['variants' => is_array($decoded) ? $decoded : null]);
        }
    }
}
