<?php

declare(strict_types=1);

namespace App\Features\User\Exceptions;

class UserAlreadyExistsException extends \Exception
{
    public function __construct(string $message = 'Usuario ja cadastrado!', int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
