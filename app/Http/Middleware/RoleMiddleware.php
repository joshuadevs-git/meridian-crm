<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        $user = $request->user();

        // User is not logged in
        if (!$user) {
            abort(401);
        }

        // User has no role
        if (!$user->role) {
            abort(403, 'No role assigned to this account.');
        }

        // User role is not allowed
        if (!in_array($user->role->name, $roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}