<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'budget_id' => ['required', 'exists:budgets,id'],
            'expense_name' => ['required', 'string', 'max:255'],
            'expense_amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}

