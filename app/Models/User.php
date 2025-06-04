<?php

namespace App\Models;

use Filament\Panel;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Cashier\Billable;
use Laravel\Scout\Searchable;
use function App\Support\tenant;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use Billable;
    use HasFactory;
    use HasUlids;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = ['created_at'];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return true;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'last_name'])
            ->saveSlugsTo('username');
    }

    public function canAccessTenant(\Illuminate\Database\Eloquent\Model $tenant): bool
    {
        return $this->teams()->whereKey($tenant)->exists();
    }


    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }
    public function getFilamentName(): string
    {
        return $this->full_name;
    }
   
    public function getCurrentTeam()
    {
        $currentTeam = tenant(Team::class); // Obtém o ID do time logado (ou use outra forma de obter o ID do time atual).

        return $this->belongsToMany(Team::class)->withPivot('commission')
            ->wherePivot('team_id', $currentTeam->id)
            ->first();
    }
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar ? Storage::url($this->avatar) : $this->defaultProfilePhotoUrl;
    }
    /**
     * RELATIONSHIPS
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * ATTRIBUTES
     */
    public function getNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
    public function getDefaultProfilePhotoUrlAttribute(): string
    {
        return 'https://ui-avatars.com/api/?name='.
            urlencode($this->getFilamentName()).
            '&color=FFFFFF&background=09090b';
    }
}
