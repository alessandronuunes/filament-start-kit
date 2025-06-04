<?php

declare(strict_types=1);

namespace App\Providers;

use function App\Support\tenant;
use Closure;
use LogicException;

use App\Filament\Pages\Billing\Plans;
use App\Http\Middleware\VerifyBillableIsSubscribed;
use App\Models\Team;

use Filament\Billing\Providers\Contracts\Provider;
use Illuminate\Http\RedirectResponse;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Cashier;

class BillingProvider implements Provider
{
    public function getRouteAction(): string|Closure|array
    {
        return static function (): RedirectResponse {
            $tenant = tenant(Team::class);

            if ($tenant::class !== Cashier::$customerModel) {
                throw new LogicException('Filament tenant does not match the Cashier customer model');
            }

            if (! in_array(Billable::class, class_uses_recursive($tenant), true)) {
                throw new LogicException('Tenant model does not use Cashier Billable trait');
            }

            if (! $tenant->hasStripeId()) {
                $tenant->createAsStripeCustomer();
            }

            return $tenant->redirectToBillingPortal(Plans::getUrl());
        };
    }

    public function getSubscribedMiddleware(): string
    {
        return VerifyBillableIsSubscribed::class;
    }
}
