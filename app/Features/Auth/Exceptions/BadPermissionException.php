<?php

declare(strict_types=1);

namespace App\Features\Auth\Exceptions;

class BadPermissionException extends \Exception
{
    public function __construct(string $message = 'Usuario não tem permissão para executar esta ação!', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}
