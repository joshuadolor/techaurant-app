<?php

return [
    'headers' => [
        'x-frame-options' => 'SAMEORIGIN',
        'x-content-type-options' => 'nosniff',
        'x-xss-protection' => '1; mode=block',
        'referrer-policy' => 'strict-origin-when-cross-origin',
        'permissions-policy' => 'camera=(), microphone=(), geolocation=()',
        'content-security-policy' => [
            'default-src' => ["'self'"],
            'script-src' => ["'self'", "'unsafe-inline'"],
            'style-src' => ["'self'", "'unsafe-inline'"],
            'img-src' => ["'self'", 'data:', 'https:'],
            'connect-src' => ["'self'"],
            'font-src' => ["'self'"],
            'object-src' => ["'none'"],
            'media-src' => ["'self'"],
            'frame-src' => ["'none'"],
        ],
    ],
    'password' => [
        'min_length' => 12,
        'require_special_chars' => true,
        'require_numbers' => true,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_uncompromised' => true,
    ],
    'session' => [
        'lifetime' => 120,
        'expire_on_close' => true,
        'encrypt' => true,
        'secure' => true,
        'http_only' => true,
        'same_site' => 'lax',
    ],
    'rate_limiting' => [
        'api' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'auth' => [
            'max_attempts' => 5,
            'decay_minutes' => 30,
        ],
    ],
];