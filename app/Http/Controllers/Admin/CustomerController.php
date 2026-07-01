<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CustomerRequest;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class CustomerController extends ResourceController
{
    public function __construct(CustomerRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function viewPath(): string
    {
        return 'admin.customers';
    }

    protected function resourceName(): string
    {
        return 'customers';
    }

    protected function relations(): array
    {
        return ['salesInvoices'];
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $this->repository->create($request->validated());

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    public function update(CustomerRequest $request, int $id): RedirectResponse
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }
}

