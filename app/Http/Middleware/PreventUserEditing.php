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

        // For regular users, block edit, update, and delete requests
        $method = $request->method();
        if (in_array($method, ['PUT', 'PATCH', 'DELETE']) ||
            ($method === 'POST' && !str_contains($request->path(), 'documents'))) {
            abort(403, 'You do not have permission to edit or delete records.');
        }

        return $next($request);
    }
}
