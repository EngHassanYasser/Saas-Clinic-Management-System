<?php

namespace App\Http\Middleware;

use App\Enums\EnRoleType;use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $allowedRoles = array_map(
            fn (string $role) =>EnRoleType::from($role),
            $roles
        );

        if (! in_array($user->type, $allowedRoles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
