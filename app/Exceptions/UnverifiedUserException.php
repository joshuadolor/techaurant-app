<?php

namespace App\Exceptions;

use Exception;

class UnverifiedUserException extends ApiException
{
    public function __construct(string $message = 'Email not verified. Please check your email for verification link.', array $data = [])
    {
        $data['code'] = 101;
        parent::__construct($message, 403, $data);
    }
}
