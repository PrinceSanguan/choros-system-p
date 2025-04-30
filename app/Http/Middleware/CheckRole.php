<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || ($role === 'admin' && !$request->user()->isAdmin())) {
            abort(403, 'Unauthorized action.');
        }

        // For users with specific region access
        if (in_array($role, ['RMFB4A', 'RMFB4B', 'RMFB5'])) {
            if ($request->user()->username !== $role) {
                abort(403, 'Unauthorized region access.');
            }
        }

        return $next($request);
    }
}
