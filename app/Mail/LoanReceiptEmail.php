<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanReceiptEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Loan $loan
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibo de Empréstimo',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'user.mail.products_loan_received',
            with: [
                'userReceiverName' => $this->loan->userReceiver->name,
                'loanedProducts' => $this->loan->loanedProducts,
                'loanDate' => $this->loan->created_at->format('d/m/Y H:i'),
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
