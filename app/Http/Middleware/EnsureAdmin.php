<?php

namespace App\Http\Middleware;

use App\Enums\RecordStatus;
use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $role = $user?->role instanceof UserRole ? $user->role->value : $user?->role;
        $status = $user?->status instanceof RecordStatus ? $user->status->value : $user?->status;

        if (! $user || $role !== UserRole::Admin->value || $status !== RecordStatus::Active->value) {
            abort(403, 'You are not authorized to access this area.');
        }

        return $next($request);
    }
}
