<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanSummaryEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public $loans,
        public $user
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resumo dos Empréstimos',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'user.mail.loan_summary',
            with: [
                'loans' => $this->loans,
                'user'  => $this->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
