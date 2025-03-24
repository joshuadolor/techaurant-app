<?php

namespace App\Exceptions;

use Exception;

class AccountLockedException extends ApiException
{
    public function __construct(string $message = 'Account is locked')
    {
        parent::__construct($message, 403, ['code' => 103]);
    }
} 