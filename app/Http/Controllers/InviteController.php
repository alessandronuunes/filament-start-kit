<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\AddTeamMember;
use App\Models\Invitation;
use App\Models\Team;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;

class InviteController extends Controller
{
    public function __construct(
        private readonly AddTeamMember $addTeamMember
    ) {
    }

    public function accept(string $token): RedirectResponse
    {
        $invitation = null;

        // Check if the token is a team invitation code
        $team = Team::where('invitation_code', $token)->first();

        // If the token is not a team invitation code, check if it is an invitation ID
        if (! $team) {
            $invitation = Invitation::findOrFail($token);
            $team = $invitation->team;
        }

        // Add the user to the team
        $this->addTeamMember
            ->handle($team, $invitation ? $invitation->email : auth()->user()->email);

        // Delete the invitation
        $invitation && $invitation->delete();

        // Redirect the user
        return redirect(url(Filament::getHomeUrl()));
    }
}
