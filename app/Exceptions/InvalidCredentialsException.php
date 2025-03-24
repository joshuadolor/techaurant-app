<?php

namespace App\Exceptions;

class InvalidCredentialsException extends ApiException
{
    public function __construct(string $message = 'Invalid credentials')
    {
        parent::__construct($message, 401, ['code' => 102]);
    }
} 