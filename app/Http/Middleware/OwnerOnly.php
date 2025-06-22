<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerOnly
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->role !== Role::SUPER_ADMIN->value) {
            $request->request->add(['owner_id' => $user->id]);

            $filters = $request->input('filters', []);
            $filters['owner_id'] = $user->id;
            $request->merge(['filters' => $filters]);
        }

        return $next($request);
    }
}
