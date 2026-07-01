<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id ?? $this->route('customer');

        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                Rule::unique('customers', 'email')->ignore($customerId),
            ],
            'phone' => ['nullable', 'regex:/^[0-9+\-\s]{7,20}$/'],
            'address' => ['nullable', 'string'],
            'gst_number' => [
                'nullable',
                'regex:/^[0-9A-Z]{15}$/',
                Rule::unique('customers', 'gst_number')->ignore($customerId),
            ],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}

