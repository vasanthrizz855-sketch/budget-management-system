<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return back()->with('success', 'Profile updated successfully.');
    }

    public function editPassword(): View
    {
        return view('admin.profile.password');
    }

    public function updatePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}

