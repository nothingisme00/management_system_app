<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Get authenticated user
                $user = Auth::guard($guard)->user();

                // Redirect to role-specific dashboard with no-cache headers
                $redirect = match ($user->role->name) {
                    'Admin' => redirect()->route('dashboard.admin'),
                    'HRD' => redirect()->route('dashboard.hrd'),
                    'Manager' => redirect()->route('dashboard.manager'),
                    'Karyawan' => redirect()->route('dashboard.karyawan'),
                    default => redirect('/dashboard'),
                };

                // Force no-cache on redirect response to prevent viewing cached login page
                return $redirect
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            }
        }

        return $next($request);
    }
}
