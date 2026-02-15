<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:9999'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_email' => ['required', 'email', 'max:255'],
            'shipping_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_province' => ['required', 'string', 'max:100'],
            'shipping_zip' => ['nullable', 'string', 'max:10'],
            
            'payment_method' => ['required', 'in:cod,gcash,bank_transfer'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'shipping_name' => 'full name',
            'shipping_email' => 'email address',
            'shipping_phone' => 'phone number',
            'shipping_address' => 'street address',
            'shipping_city' => 'city',
            'shipping_province' => 'province',
            'shipping_zip' => 'postal code',
            'payment_method' => 'payment method',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'items.required' => 'Your cart is empty. Please add items before checking out.',
            'items.min' => 'Your cart must contain at least one item.',
            'items.*.product_id.exists' => 'One or more products in your cart are no longer available.',
            'payment_method.in' => 'Please select a valid payment method: Cash on Delivery, GCash, or Bank Transfer.',
        ];
    }
}
