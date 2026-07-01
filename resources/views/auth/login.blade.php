@extends('layouts.guest')

@section('content')
<div class="card auth-card p-4 p-md-5">
    <div class="text-center mb-4">
        <h1 class="h3 fw-bold mb-2">{{ config('app.name') }}</h1>
        <p class="text-muted mb-0">Sign in to manage invoices, budgets, payments, and reports.</p>
    </div>

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
@endsection
