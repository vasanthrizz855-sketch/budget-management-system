@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-1">Record Payment</h1>
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
</div>

<div class="card soft-card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.payments.store') }}">
            @csrf
            @include('admin.payments._form')
            <div class="mt-4">
                <button class="btn btn-primary">Save Payment</button>
                <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

