<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AllowExpiredTokens
{
    public function handle(Request $request, Closure $next)
    {
        $refreshToken = $request->cookie('refresh_token');
        
        if (!$refreshToken) {
            return response()->json(['message' => 'Refresh token not found'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($refreshToken);

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
