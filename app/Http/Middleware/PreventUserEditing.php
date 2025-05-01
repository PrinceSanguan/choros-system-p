<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventUserEditing
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow admin and region-specific roles to edit
        if ($request->user() && ($request->user()->isAdmin() || in_array($request->user()->role, ['RMFB4A', 'RMFB4B', 'RMFB5']))) {
            return $next($request);
        }

        // For regular users, block only edit/update requests (PUT, PATCH)
        // Allow POST (add) and DELETE (delete) requests
        $method = $request->method();
        if (in_array($method, ['PUT', 'PATCH'])) {
            abort(403, 'You do not have permission to edit records.');
        }

        return $next($request);
    }
}
