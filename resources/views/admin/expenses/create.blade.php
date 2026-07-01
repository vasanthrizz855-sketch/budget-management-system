@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-1">Add Expense</h1>
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.expenses.index') }}">Expenses</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
</div>

<div class="card soft-card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.expenses.store') }}">
            @csrf
            @include('admin.expenses._form')
            <div class="mt-4">
                <button class="btn btn-primary">Save Expense</button>
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

