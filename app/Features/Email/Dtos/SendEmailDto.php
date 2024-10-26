<?php

declare(strict_types=1);

namespace App\Features\Email\Dtos;

use App\Features\User\ValueObjects\Email;
use Illuminate\Mail\Mailable;

class SendEmailDto
{
    public function __construct(
        public Email $to,
        public string $name,
        public Mailable $body,
    ) {
    }
}
