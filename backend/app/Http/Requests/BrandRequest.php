<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand')?->id;

        return [
            'name'        => ['required', 'string', 'max:255', "unique:brands,name,{$brandId}"],
            'description' => ['nullable', 'string'],
            'website'     => ['nullable', 'url', 'max:500'],
            'is_active'   => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
