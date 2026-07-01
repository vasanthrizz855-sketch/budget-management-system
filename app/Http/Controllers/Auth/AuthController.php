<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RecordStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        $credentials['role'] = UserRole::Admin->value;
        $credentials['status'] = RecordStatus::Active->value;

        if (! Auth::attempt($credentials, (bool) $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'The provided credentials are invalid or the account is inactive.']);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('success', 'Welcome back.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink($request->validated());

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withInput()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm(string $token, Request $request): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        $status = Password::reset(
            $credentials,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
