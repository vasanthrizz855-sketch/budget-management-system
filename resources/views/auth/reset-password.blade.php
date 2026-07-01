@extends('layouts.guest')

@section('content')
<div class="card auth-card p-4 p-md-5">
    <h1 class="h4 fw-bold mb-2">Reset Password</h1>
    <p class="text-muted">Create a new password for your account.</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="{{ $email }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100" type="submit">Reset Password</button>
    </form>
</div>
@endsection

