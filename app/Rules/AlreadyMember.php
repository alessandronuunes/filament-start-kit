<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\Team;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Contracts\Validation\ValidationRule;

class AlreadyMember implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tenant = Filament::getTenant();

        tap(explode('.', $attribute), function (array $parts) use ($value, $fail, $tenant) {
            if ($tenant instanceof Team && $tenant->hasMemberWithEmail($value)) {
                $fail(__('validation.already_member', ['attribute' => end($parts)]));
            }
        });
    }
}
