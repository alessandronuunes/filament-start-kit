<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Actions\SubscribeAction;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends \Filament\Pages\Dashboard
{
    public static function getNavigationLabel(): string
    {
        return __('Dashboard');
    }

    public function getSubheading(): string | Htmlable | null
    {

        return 'Seja bem vindo'.' '.auth()->user()->first_name.' '.auth()->user()->last_name;
    }

    public function subscribeAction(): Action
    {
        return SubscribeAction::make()
            // ->modalHeading(__('Selecione o plano ideal para você'))
            // ->modalDescription(__('Obtenha acesso a todos os recursos com um de nossos planos de assinatura.'))
            ->extraAttributes(['class' => 'asdfsdf'])
            ->plan('premium') // Defina o plano desejado (default, premium, etc.)
            ->brandLogo(asset('images/logo.png'));
    }
}
