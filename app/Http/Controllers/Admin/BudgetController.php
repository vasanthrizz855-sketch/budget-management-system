<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BudgetType;
use App\Http\Requests\Admin\BudgetRequest;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class BudgetController extends ResourceController
{
    public function __construct(BudgetRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function viewPath(): string
    {
        return 'admin.budgets';
    }

    protected function resourceName(): string
    {
        return 'budgets';
    }

    protected function relations(): array
    {
        return ['expenses'];
    }

    protected function formViewData(?int $id = null): array
    {
        return [
            'budgetTypes' => BudgetType::options(),
        ];
    }

    public function store(BudgetRequest $request): RedirectResponse
    {
        $this->repository->create($request->validated());

        return redirect()->route('admin.budgets.index')->with('success', 'Budget created successfully.');
    }

    public function update(BudgetRequest $request, int $id): RedirectResponse
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('admin.budgets.index')->with('success', 'Budget updated successfully.');
    }
}

