<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use function App\Support\tenant;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyTenantScopes
{
    public function handle(Request $request, Closure $next): Response
    {
        $team = tenant(Team::class);
        User::addGlobalScope(
            fn (Builder $query) => $query->whereHas('teams', fn (Builder $query) => $query->where('team_id', $team->id))
        );

        Invitation::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant())
        );

        return $next($request);
    }
}
