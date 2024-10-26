<?php

declare(strict_types=1);

namespace App\Features\User\ValueObjects;

readonly class Email
{
    public function __construct(
        public string $value
    ) {
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email invalido!');
        }
    }
}
