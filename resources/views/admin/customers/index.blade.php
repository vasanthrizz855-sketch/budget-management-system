@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Customers</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Customers</li>
        </ol>
    </div>
    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">Add Customer</a>
</div>

<div class="card soft-card">
    <div class="card-body">
        <form class="row g-2 mb-4" method="GET" action="{{ route('admin.customers.index') }}">
            <div class="col-md-10">
                <input type="search" name="search" class="form-control" placeholder="Search customer..." value="{{ $search }}">
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
                    <th>Email</th>
                    <th>Phone</th>
                    <th>GST</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $customer)
                    <tr>
                        <td>{{ $customer->customer_code }}</td>
                        <td>{{ $customer->customer_name }}</td>
                        <td>{{ $customer->email ?? '-' }}</td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>{{ $customer->gst_number ?? '-' }}</td>
                        <td><span class="badge bg-{{ status_badge_class($customer->status instanceof \App\Enums\RecordStatus ? $customer->status->value : $customer->status) }}">{{ $customer->status instanceof \App\Enums\RecordStatus ? $customer->status->label() : $customer->status }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this customer?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            No customers found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection

