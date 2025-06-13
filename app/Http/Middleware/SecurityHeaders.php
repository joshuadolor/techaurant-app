<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->remove('X-Powered-By');
        
        $headers = Config::get('security.headers');
        
        foreach ($headers as $key => $value) {
            if (is_array($value)) {
                $value = collect($value)->map(fn($v) => is_array($v) ? implode(' ', $v) : $v)->join('; ');
            }
            $response->headers->set($key, $value);
        }
        
        return $response;
    }
}