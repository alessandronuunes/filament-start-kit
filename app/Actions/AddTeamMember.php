<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;

class AddTeamMember
{
    /**
     * Add a new team member to the given team.
     *
     * @throws AuthorizationException
     */
    public function handle(Team $team, string $email): void
    {
        $this->validate($team, $email);

        $user = User::where('email', $email)->firstOrFail();

        $team->members()->attach($user);
    }

    /**
     * Validate the add member operation.
     */
    protected function validate(Team $team, string $email): void
    {
        Validator::make([
            'email' => $email,
        ], $this->rules(), [
            'email.exists' => __('email_not_exists'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $email)
        )->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for adding a team member.
     *
     * @return array<string>
     */
    protected function rules(): array
    {
        return ['email' => 'required|email|exists:users'];
    }

    /**
     * Ensure that the user is not already on the team.
     */
    protected function ensureUserIsNotAlreadyOnTeam(Team $team, string $email): Closure
    {
        return static function (\Illuminate\Validation\Validator $validator) use ($team, $email) {
            $validator->errors()->addIf(
                $team->hasMemberWithEmail($email),
                'email',
                __('already_on_team')
            );
        };
    }
}
