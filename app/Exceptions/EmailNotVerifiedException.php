<?php

namespace App\Exceptions;

class EmailNotVerifiedException extends ApiException
{
    public function __construct(string $message = 'Email not verified', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, 402, $code, $previous);
    }
}