<?php

declare(strict_types=1);

namespace App\Features\Auth\Exceptions;

class UserNotFoundException extends \Exception
{
    public function __construct(string $message = 'Usuario não encontrado!', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}
