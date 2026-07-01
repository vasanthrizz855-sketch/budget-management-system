<?php

namespace App\Http\Requests\Admin;

use App\Enums\BudgetType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'budget_name' => ['required', 'string', 'max:255'],
            'budget_type' => ['required', Rule::in(array_keys(BudgetType::options()))],
            'allocated_amount' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
