<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ExpenseRequest;
use App\Models\Budget;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class ExpenseController extends ResourceController
{
    public function __construct(ExpenseRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function viewPath(): string
    {
        return 'admin.expenses';
    }

    protected function resourceName(): string
    {
        return 'expenses';
    }

    protected function relations(): array
    {
        return ['budget'];
    }

    protected function formViewData(?int $id = null): array
    {
        return [
            'budgets' => Budget::query()->latest()->get(),
        ];
    }

    public function store(ExpenseRequest $request): RedirectResponse
    {
        $this->repository->create($request->validated());

        return redirect()->route('admin.expenses.index')->with('success', 'Expense created successfully.');
    }

    public function update(ExpenseRequest $request, int $id): RedirectResponse
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }
}

