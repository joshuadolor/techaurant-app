<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;

class AdminOnly
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRoles = [Role::ADMIN, Role::SUPER_ADMIN];
        
        if (!$request->user() || !in_array($request->user()->role, $allowedRoles)) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
