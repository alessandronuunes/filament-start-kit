<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\TeamObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;
use Relaticle\CustomFields\Models\CustomField;

/**
 * @property int $owner_id
 * @property string|null $invitation_code
 */
#[ObservedBy(TeamObserver::class)]
class Team extends Model
{
    use Billable;
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'invitation_code',
        'commission',
        'is_free',
    ];

    protected function casts(): array
    {
        return [
            'commission' => 'array',
            'is_free' => 'boolean',
        ];
    }

    public function onGenericTrial(): bool
    {
        if ($this->is_free === 1) {
            return false;
        }

        return $this->trial_ends_at !== null && $this->trial_ends_at > now();
    }


    /**  Verifica se o team tem acesso gratuito */
    public function hasFreeAccess(): bool
    {
        return $this->is_free === 1;
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function hasMemberWithEmail(string $email): bool
    {
        return $this->members()
            ->where('email', $email)
            ->exists();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

}
