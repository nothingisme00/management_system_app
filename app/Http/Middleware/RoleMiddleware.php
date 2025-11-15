<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Contracts\Services\AuthorizationServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Role Middleware
 *
 * Authorizes access based on user roles using AuthorizationService.
 */
class RoleMiddleware
{
    /**
     * Constructor with dependency injection.
     */
    public function __construct(
        protected AuthorizationServiceInterface $authorizationService
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Check if user has required role using authorization service
        if (! $this->authorizationService->hasRole($request->user(), $role)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
