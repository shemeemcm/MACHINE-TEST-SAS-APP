<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return abort(401, 'Unauthorized.');
        }

        // Super Admin doesn't need an organization_id to access the system
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Regular users MUST belong to an organization
        if (!$user->organization_id) {
            return abort(403, 'You do not belong to an active organization.');
        }

        return $next($request);
    }
}
