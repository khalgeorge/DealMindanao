<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'region'           => ['nullable', 'string', 'max:255'],
            'contact_person'   => ['nullable', 'string', 'max:255'],
            'contact_email'    => ['nullable', 'email', 'max:255'],
            'contact_phone'    => ['nullable', 'string', 'max:30'],
            'internal_notes'   => ['nullable', 'string', 'max:2000'],
            'is_active'        => ['nullable', 'boolean'],
            'is_verified'      => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'contact_person' => 'contact person',
            'contact_email'  => 'contact email',
            'contact_phone'  => 'contact phone',
            'internal_notes' => 'internal notes',
            'is_verified'    => 'verified status',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
