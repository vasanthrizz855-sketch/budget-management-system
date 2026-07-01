@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Products</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
</div>

<div class="card soft-card">
    <div class="card-body">
        <form class="row g-2 mb-4" method="GET" action="{{ route('admin.products.index') }}">
            <div class="col-md-10">
                <input type="search" name="search" class="form-control" placeholder="Search product..." value="{{ $search }}">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-primary">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Unit Price</th>
                    <th>Tax %</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $product)
                    <tr>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ format_money($product->unit_price) }}</td>
                        <td>{{ number_format((float) $product->tax_percentage, 2) }}%</td>
                        <td><span class="badge bg-{{ status_badge_class($product->status instanceof \App\Enums\RecordStatus ? $product->status->value : $product->status) }}">{{ $product->status instanceof \App\Enums\RecordStatus ? $product->status->label() : $product->status }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">No products found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>
</div>
@endsection

