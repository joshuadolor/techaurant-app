<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AllowExpiredTokens
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        \Log::info('Token: ' . $token);
        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the token in the database
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Set the user on the request even if token is expired
        $request->setUserResolver(function () use ($accessToken) {
            return $accessToken->tokenable;
        });

        return $next($request);
    }
}
