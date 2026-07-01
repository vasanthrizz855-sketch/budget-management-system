@php
    $budget = $item ?? null;
    $budgetType = data_get($budget, 'budget_type');
    $budgetType = $budgetType instanceof \App\Enums\BudgetType ? $budgetType->value : ($budgetType ?? 'monthly');
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Budget Name</label>
        <input type="text" name="budget_name" class="form-control @error('budget_name') is-invalid @enderror" value="{{ old('budget_name', data_get($budget, 'budget_name', '')) }}">
        @error('budget_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Budget Type</label>
        <select name="budget_type" class="form-select @error('budget_type') is-invalid @enderror">
            @foreach($budgetTypes as $value => $label)
                <option value="{{ $value }}" @selected(old('budget_type', $budgetType) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('budget_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Allocated Amount</label>
        <input type="number" step="0.01" name="allocated_amount" class="form-control @error('allocated_amount') is-invalid @enderror" value="{{ old('allocated_amount', data_get($budget, 'allocated_amount', '')) }}">
        @error('allocated_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', optional(data_get($budget, 'start_date'))->format('Y-m-d') ?? '') }}">
        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', optional(data_get($budget, 'end_date'))->format('Y-m-d') ?? '') }}">
        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Notes</label>
        <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', data_get($budget, 'notes', '')) }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

