<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $url,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verifique seu endereço de e-mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.verify-email',
            with: ['url' => $this->url],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
