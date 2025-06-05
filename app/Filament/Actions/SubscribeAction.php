<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use Closure;
use App\Models\Team;
use Illuminate\View\View;
use Filament\Actions\Action;
use App\Helpers\Cashier\Stripe;
use function App\Support\tenant;
use Illuminate\Support\Facades\Log;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;

class SubscribeAction extends Action
{
    protected string|Htmlable|Closure|null $brandLogo = null;

    protected string|Htmlable|Closure|null $heading = null;

    protected string|Htmlable|Closure|null $subheading = null;

    protected string|Closure|null $selectedPlan = 'default';

    protected function setUp(): void
    {
        $team = tenant(Team::class);

        $this->name('subscribe');

        $this->modalWidth(MaxWidth::FourExtraLarge);

        
        $this->modalContent(function () {
            // Avalia as closures antes de passar para a view
            $selectedPlan = $this->getSelectedPlan();
            $brandLogo = $this->getBrandLogo();

            return view('filament.actions.subscribe.content', [
                'selectedPlan' => $selectedPlan,
                'brandLogo' => $brandLogo,
            ]);
        });

        $this->form([
            // O formulário agora é controlado por LiveWire e Alpine.js
        ]);

        $this->registerModalActions([
            Action::make('checkout')
                ->label(__('Prosseguir para Pagamento'))
                ->color('primary')
                ->size('lg')
                ->action(function (array $arguments, Action $action): void {
                    try {
                        // Obtém os dados da action corretamente
                        $actionData = $action->getLivewire()->mountedActionsData;
                        // Log para depuração
                        Log::info('Checkout action data', ['actionData' => $actionData]);

                        // Primeiro, tenta obter o billing_period dos argumentos passados
                        $billingPeriod = $arguments['billing_period'] ?? null;

                        // Se não encontrar nos argumentos, tenta obter dos dados montados da action
                        if (empty($billingPeriod)) {
                            $billingPeriod = data_get($actionData, $this->getName().'.billing_period');
                        }

                        Log::info('Billing period encontrado', ['billingPeriod' => $billingPeriod]);

                        if (empty($billingPeriod)) {
                            // Usa a classe Notification do Filament
                            Notification::make()
                                ->title(__('Erro'))
                                ->body(__('Nenhum plano selecionado. Por favor, selecione um plano e período de cobrança.'))
                                ->danger()
                                ->send();

                            return;
                        }

                        // Redireciona para o checkout do Stripe
                        Stripe::fromConfig()->checkoutUrl($billingPeriod);

                    } catch (\Exception $e) {
                        Log::error('Erro no checkout do Stripe', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);

                        Notification::make()
                            ->title(__('Erro'))
                            ->body(__('Não foi possível processar o pagamento. Por favor, tente novamente.'))
                            ->danger()
                            ->send();
                    }
                }),
        ]);
        //
        $this->closeModalByClickingAway($team->onGenericTrial());
        $this->closeModalByEscaping($team->onGenericTrial());
        $this->modalCloseButton(true);
        $this->modalCancelAction(false);
        $this->modalSubmitAction(false);
        $this->modalHeading(''); // Removemos o título padrão para usar nosso próprio no template

        $this->extraModalWindowAttributes([
            'class' => 'subscription-modal-wrapper',
        ]);
    }

    public function brandLogo(string|Htmlable|Closure|null $logo): static
    {
        $this->brandLogo = $logo;

        return $this;
    }

    public function getBrandLogo(): string|Htmlable|null
    {
        return $this->evaluate($this->brandLogo);
    }

    public function plan(string|Closure $plan): static
    {
        $this->selectedPlan = $plan;

        return $this;
    }

    public function getSelectedPlan(): string
    {
        return $this->evaluate($this->selectedPlan);
    }
}
