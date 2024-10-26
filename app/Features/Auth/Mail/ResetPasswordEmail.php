<?php

declare(strict_types=1);

namespace App\Features\Auth\Mail;

use App\Features\Auth\Models\PasswordResetToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public PasswordResetToken $passwordResetToken,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Solicitação de alteração de senha',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'user.mail.reset_password',
            with: [
                'userName' => $this->passwordResetToken->user->name,
                'token' => $this->passwordResetToken->token,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
