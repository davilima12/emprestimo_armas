<?php

declare(strict_types=1);

namespace App\Features\Auth\Exceptions;

class PasswordAlreadySavedException extends \Exception
{
    public function __construct(string $message = 'Senha ja cadastrada para esse email!', int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
