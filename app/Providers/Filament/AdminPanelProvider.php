<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\Team;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Auth\Register;
use App\Providers\BillingProvider;
use Filament\Support\Colors\Color;
use App\Filament\Pages\MyProfilePage;
use Illuminate\Support\Facades\Route;
use App\Filament\Pages\Team\RegisterTeam;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\InviteController;
use App\Http\Middleware\ApplyTenantScopes;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
// use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->loginRouteSlug('auth/login')
            ->registration(Register::class)
            ->registrationRouteSlug('auth/register')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->plugin(
                BreezyCore::make()
                    ->customMyProfilePage(MyProfilePage::class)
                    ->enableTwoFactorAuthentication()
                    ->myProfile(
                        hasAvatars: true,
                        slug: 'me'
                    )
                    ->passwordUpdateRules(
                        rules: [
                            Password::default()
                                ->letters()
                                ->mixedCase()
                                ->numbers()
                                ->symbols()
                                ->mixedCase()
                                ->uncompromised(3),
                        ],
                    ),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authenticatedRoutes(function () {
                Route::get('/invite/{invitation_code}/accept', [InviteController::class, 'accept'])
                    ->middleware(['signed'])
                    ->name('invite.accept');
            })
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->topNavigation()
            ->registration(Register::class)
            ->registrationRouteSlug('auth/register')
            ->tenantMiddleware([
                ApplyTenantScopes::class,
            ], isPersistent: true)
            ->tenantRegistration(RegisterTeam::class)
            ->tenant(model: Team::class, slugAttribute: 'slug', ownershipRelationship: 'teams') // Adicionado aqui
            ->tenantBillingProvider(new BillingProvider)
            ->requiresTenantSubscription()
            ;
    }
}
