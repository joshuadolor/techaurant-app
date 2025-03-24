<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected $code;
    protected $data;

    public function __construct(string $message, int $code = 400, array $data = [])
    {
        parent::__construct($message);
        $this->code = $code;
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }
} 