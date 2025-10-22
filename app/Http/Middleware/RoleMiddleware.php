<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // cek apakah user punya salah satu role yang diizinkan
        if (!$user->roles()->whereIn('name', $roles)->exists()) {
            abort(403, 'Access denied: you do not have the required role.');
        }

        return $next($request);
    }
}
