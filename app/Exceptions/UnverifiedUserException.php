<?php

namespace App\Exceptions;

use Exception;

class UnverifiedUserException extends ApiException
{
    public function __construct(string $message = 'Email not verified')
    {
        parent::__construct($message, 403, ['code' => 101]);
    }
} 