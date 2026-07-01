@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-1">Edit Budget</h1>
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.budgets.index') }}">Budgets</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</div>

<div class="card soft-card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.budgets.update', $item) }}">
            @csrf
            @method('PUT')
            @include('admin.budgets._form', ['item' => $item])
            <div class="mt-4">
                <button class="btn btn-primary">Update Budget</button>
                <a href="{{ route('admin.budgets.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

