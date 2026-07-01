@php
    $expense = $item ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Budget</label>
        <select name="budget_id" class="form-select @error('budget_id') is-invalid @enderror">
            <option value="">Select budget</option>
            @foreach($budgets as $budget)
                <option value="{{ $budget->id }}" @selected(old('budget_id', data_get($expense, 'budget_id')) == $budget->id)>{{ $budget->budget_name }}</option>
            @endforeach
        </select>
        @error('budget_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Expense Name</label>
        <input type="text" name="expense_name" class="form-control @error('expense_name') is-invalid @enderror" value="{{ old('expense_name', data_get($expense, 'expense_name', '')) }}">
        @error('expense_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Expense Amount</label>
        <input type="number" step="0.01" name="expense_amount" class="form-control @error('expense_amount') is-invalid @enderror" value="{{ old('expense_amount', data_get($expense, 'expense_amount', '')) }}">
        @error('expense_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Expense Date</label>
        <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', optional(data_get($expense, 'expense_date'))->format('Y-m-d') ?? '') }}">
        @error('expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', data_get($expense, 'description', '')) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

