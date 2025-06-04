<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Livewire\MyCustomProfile;
use App\Models\Team;

use function App\Support\tenant;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Jeffgreco13\FilamentBreezy\Livewire\TwoFactorAuthentication;
use Jeffgreco13\FilamentBreezy\Livewire\UpdatePassword;

class MyProfilePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament-breezy::filament.pages.my-profile';

    public function hasOwnerTeam(): bool
    {
        return tenant(Team::class)->owner_id === Auth::user()->id;
    }

    public function getTitle(): string
    {
        return __('filament-breezy::default.profile.my_profile');
    }

    public function getHeading(): string
    {
        return __('filament-breezy::default.profile.my_profile');
    }

    public function getSubheading(): ?string
    {
        return __('filament-breezy::default.profile.subheading') ?? null;
    }

    public static function getSlug(): string
    {
        /** @phpstan-ignore-next-line  */
        return filament('filament-breezy')->slug();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-breezy::default.profile.profile');
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @phpstan-ignore-next-line  */
        return filament('filament-breezy')->shouldRegisterNavigation('myProfile');
    }

    public static function getNavigationGroup(): ?string
    {
        /** @phpstan-ignore-next-line  */
        return filament('filament-breezy')->getNavigationGroup('myProfile');
    }

    public function getProfileComponents(): array
    {
        return [
            'personal_info' => MyCustomProfile::class,
        ];
    }

    public function getSecurityComponents(): array
    {
        return [
            'update_password' => UpdatePassword::class,
            'two_factor_authentication' => TwoFactorAuthentication::class,
        ];
    }

    public function getRegisteredMyProfileComponents(): array
    {
        return filament('filament-breezy')->getRegisteredMyProfileComponents();
    }
}
