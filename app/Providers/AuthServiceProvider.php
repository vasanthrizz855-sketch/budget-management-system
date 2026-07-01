<?php

namespace App\Providers;

use App\Enums\RecordStatus;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(static function (?User $user, string $ability): ?bool {
            if (! $user) {
                return null;
            }

            $status = $user->status instanceof RecordStatus ? $user->status->value : $user->status;
            $role = $user->role instanceof UserRole ? $user->role->value : $user->role;

            if ($status !== RecordStatus::Active->value) {
                return false;
            }

            return $role === UserRole::Admin->value ? true : null;
        });
    }
}
