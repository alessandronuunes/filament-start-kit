<?php

declare(strict_types=1);

namespace App\Filament\Pages\Billing;

use App\Helpers\Cashier\Stripe;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Pages\Page;
use Filament\Panel;
use Illuminate\Contracts\Support\Htmlable;

class Plans extends Page implements HasActions
{
    use InteractsWithActions;

    protected static string $view = 'filament.pages.billing.plans';

    protected static bool $shouldRegisterNavigation = false;

    public function getHeading(): string|Htmlable
    {
        return __('Planos e Preços');
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('Escolha o plano que melhor se adapta às suas necessidades.');
    }

    public function hasDiscount(): bool
    {
        return Stripe::fromConfig()->hasDiscount();
    }

    public function getDiscount(): int
    {
        return Stripe::fromConfig()->discount();
    }

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return false;
    }

    public function checkoutAction(): Action
    {
        return Action::make('checkout')
            ->action(fn (array $arguments) => Stripe::fromConfig()->checkoutUrl(data_get($arguments, 'priceId')));
    }
}
