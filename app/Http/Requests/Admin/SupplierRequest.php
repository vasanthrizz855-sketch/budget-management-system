<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplierId = $this->route('supplier')?->id ?? $this->route('supplier');

        return [
            'supplier_name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                Rule::unique('suppliers', 'email')->ignore($supplierId),
            ],
            'phone' => ['nullable', 'regex:/^[0-9+\-\s]{7,20}$/'],
            'address' => ['nullable', 'string'],
            'gst_number' => [
                'nullable',
                'regex:/^[0-9A-Z]{15}$/',
                Rule::unique('suppliers', 'gst_number')->ignore($supplierId),
            ],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}

