@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card soft-card">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <button class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

