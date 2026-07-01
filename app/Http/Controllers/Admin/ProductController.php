<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductRequest;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class ProductController extends ResourceController
{
    public function __construct(ProductRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function viewPath(): string
    {
        return 'admin.products';
    }

    protected function resourceName(): string
    {
        return 'products';
    }

    protected function relations(): array
    {
        return ['purchaseInvoiceItems', 'salesInvoiceItems'];
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $this->repository->create($request->validated());

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function update(ProductRequest $request, int $id): RedirectResponse
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }
}

