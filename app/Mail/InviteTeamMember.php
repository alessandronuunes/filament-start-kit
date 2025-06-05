<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class InviteTeamMember extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly Invitation $invitation
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Junte-se à equipe').' '.$this->invitation->team->name.' '.__('em').' '.config('app.name'),
        );
    }

    public function content(): Content
    {
        $acceptUrl = URL::signedRoute('filament.app.invite.accept', ['invitation_code' => $this->invitation->id]);

        $userName = User::find($this->invitation->user_id)->getFilamentName();
        $teamName = $this->invitation->team->name;

        return new Content(
            html: 'mail.team-invitation',
            with: [
                'userName' => $userName,
                'teamName' => $teamName,
                'url' => $acceptUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
