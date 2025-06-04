<?php

declare(strict_types=1);

namespace App\Observers;

use App\Helpers\Cashier\TrialManager;
use App\Models\Team;
use Illuminate\Support\Str;

class TeamObserver
{
    public function retrieved(Team $team): void
    {
        //
    }

    public function creating(Team $team): void
    {
        $team->invitation_code = Str::random(32);
        $team->owner_id = auth()->id();
    }

    public function created(Team $team): void
    {
        TrialManager::startTrial($team);
    }

    public function updating(Team $team): void
    {
        //
    }

    public function updated(Team $team): void
    {
        //
    }

    public function saving(Team $team): void
    {
        //
    }

    public function saved(Team $team): void
    {
        //
    }

    public function deleting(Team $team): void
    {
        //
    }

    public function deleted(Team $team): void
    {
        //
    }

    public function trashed(Team $team): void
    {
        //
    }

    public function forceDeleting(Team $team): void
    {
        //
    }

    public function forceDeleted(Team $team): void
    {
        //
    }

    public function restoring(Team $team): void
    {
        //
    }

    public function restored(Team $team): void
    {
        //
    }

    public function replicating(Team $team): void
    {
        //
    }
}
