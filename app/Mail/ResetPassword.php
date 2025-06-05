<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $url,
        public int $expiration = 60,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Redefinir senha',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.reset-password',
            with: [
                'url' => $this->url,
                'expiration' => $this->expiration,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
