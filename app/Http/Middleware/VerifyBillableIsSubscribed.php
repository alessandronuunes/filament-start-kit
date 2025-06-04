<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use function App\Support\tenant;

use App\Filament\Pages\Dashboard;
use App\Helpers\Cashier\Stripe;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyBillableIsSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant(Team::class);
        
        $hasActiveSubscription = false;
        $isOnTrial = false;

        // Verificar se é um cliente com acesso gratuito
        if ($tenant->is_free) {
            return $next($request);
        }
        // Verificar se o tenant está em período de trial
        if ($tenant->trial_ends_at !== null && $tenant->trial_ends_at > Carbon::now()) {
            $isOnTrial = true;
        }

        // Verificação simples se o tenant tem alguma assinatura ativa
        if ($tenant->subscriptions()->where('stripe_status', 'active')->exists()) {
            $hasActiveSubscription = true;
        } else {
            // Verificação detalhada por cada plano (caso a verificação simples falhe)
            $stripeConfig = Stripe::fromConfig();
            
            foreach ($stripeConfig->plans() as $plan) {
                // Verifica se existe uma assinatura ativa para este tipo de plano
                if ($tenant->subscription($plan->type())?->active()) {
                    $hasActiveSubscription = true;

                    break;
                }

                // Verificação alternativa pelo product ID
                if ($tenant->subscribedToProduct($plan->productId())) {
                    $hasActiveSubscription = true;

                    break;
                }
            }
        }

        if ($isOnTrial) {
            return $next($request);
        }

        if ($hasActiveSubscription && $request->getQueryString() === 'action=subscribe') {
            return redirect(Dashboard::getUrl());
        }

        if ($hasActiveSubscription) {
            return $next($request);
        }

        if ($request->getQueryString() === 'action=subscribe') {
            return $next($request);
        }

        return redirect(Dashboard::getUrl(['action' => 'subscribe']));
    }
}
