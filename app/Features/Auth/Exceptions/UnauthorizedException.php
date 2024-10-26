<?php

declare(strict_types=1);

namespace App\Features\Auth\Exceptions;

class UnauthorizedException extends \Exception
{
    public function __construct(string $message = '', int $code = 401, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function unauthorized(): self
    {
        return new self('Usuario não autorizado!', 401);
    }
}
