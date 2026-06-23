<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Unauthorized access.');
        }

        if (!$request->user()->is_active) {
            \Illuminate\Support\Facades\Auth::logout();
            return redirect('/')->withErrors(['email' => 'Your account has been deactivated.']);
        }

        return $next($request);
    }
}
