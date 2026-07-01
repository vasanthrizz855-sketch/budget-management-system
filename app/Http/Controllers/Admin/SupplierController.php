<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SupplierRequest;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class SupplierController extends ResourceController
{
    public function __construct(SupplierRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function viewPath(): string
    {
        return 'admin.suppliers';
    }

    protected function resourceName(): string
    {
        return 'suppliers';
    }

    protected function relations(): array
    {
        return ['purchaseInvoices'];
    }

    public function store(SupplierRequest $request): RedirectResponse
    {
        $this->repository->create($request->validated());

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function update(SupplierRequest $request, int $id): RedirectResponse
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
    }
}

