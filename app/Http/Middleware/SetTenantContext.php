<?php

namespace App\Http\Middleware;

use App\Models\Clinic;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $user = $request->user();

        $clinic = Clinic::where('owner_id', $user->id)->first();
        if (! $clinic) {
            abort(403, 'Clinic context not found.');
        }

        app(TenantContext::class)->set($clinic);

        return $next($request);
    }
}
