@extends('layouts.guest')

@section('content')
<div class="card auth-card p-4 p-md-5">
    <h1 class="h4 fw-bold mb-2">Forgot Password</h1>
    <p class="text-muted">Enter your email address and we will send a reset link.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary w-100" type="submit">Send Reset Link</button>
    </form>
</div>
@endsection

