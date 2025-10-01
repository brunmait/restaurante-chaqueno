<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = strtolower((string) optional(Auth::user()->role)->name);
        if ($userRole !== strtolower($role)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}


