{{-- View para plano em período de teste --}}
@php
    // Importa configurações do Stripe
    $stripeConfig = config('stripe');
    $defaultPlan = $stripeConfig['plans']['default'] ?? null;
    $defaultMonthlyPrice = $defaultPlan['prices']['monthly']['price'] ?? 0;

    // Formata o preço para exibição
    $formattedPrice = number_format($defaultMonthlyPrice / 100, 2, ',', '.');

    // Data de término do trial
    $trialEndsAt = $record->trialEndsAt ?? null;
@endphp

<div class="w-full">
    <div class="rounded-xl overflow-hidden border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-800">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">Período de Teste</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Aproveite todos os recursos premium
                        @if($trialEndsAt)
                            até {{ $trialEndsAt }}
                        @endif
                    </p>
                </div>
                <div class="rounded-lg bg-warning-50 px-3 py-1 text-sm font-medium text-warning-700 dark:bg-warning-900/50 dark:text-warning-400">
                    Avaliação
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Coluna esquerda - Por que assinar -->
            <div class="p-6 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700">
                <div class="flex flex-col h-full">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Por que assinar agora?</h4>

                    <ul class="space-y-4">
                        <li class="flex gap-x-3">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-500 flex items-center justify-center">
                                <x-heroicon-s-check class="h-3 w-3 text-white" />
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Garanta acesso contínuo a todos os recursos</span>
                        </li>
                        <li class="flex gap-x-3">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-500 flex items-center justify-center">
                                <x-heroicon-s-check class="h-3 w-3 text-white" />
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Suporte prioritário</span>
                        </li>
                        <li class="flex gap-x-3">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-500 flex items-center justify-center">
                                <x-heroicon-s-check class="h-3 w-3 text-white" />
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Sem limite de uso durante o período</span>
                        </li>
                        <li class="flex gap-x-3">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-primary-500 flex items-center justify-center">
                                <x-heroicon-s-check class="h-3 w-3 text-white" />
                            </span>
                            <span class="text-sm text-gray-600 dark:text-gray-300">Escolha o plano que melhor se adequa à sua equipe</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Coluna direita - Plano recomendado -->
            <div class="p-6 bg-gray-50 dark:bg-gray-900">
                <div class="flex flex-col h-full">
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $defaultPlan['name'] ?? 'Plano Padrão' }}</h4>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $defaultPlan['short_description'] ?? 'Ideal para pequenas empresas' }}</p>
                    </div>

                    <div class="mb-6">
                        <p class="flex items-baseline text-gray-900 dark:text-white">
                            <span class="text-4xl font-extrabold tracking-tight">R$ {{ $formattedPrice }}</span>
                            <span class="ml-1 text-xl font-medium text-gray-500 dark:text-gray-400">/mês</span>
                        </p>
                    </div>

                    <div class="flex-grow">
                        <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Inclui:</h5>
                        </div>

                        <ul class="space-y-3">
                            @foreach($defaultPlan['features'] ?? [] as $feature)
                                <li class="flex items-center gap-x-3">
                                    <x-heroicon-s-check-circle class="h-5 w-5 flex-none text-primary-500" />
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-6">
                        <button
                            type="button"
                            onclick="Livewire.dispatch('signature-redirect-to-subscribe')"
                            class="w-full rounded-lg border border-primary-600 bg-primary-600 px-5 py-2.5 text-center text-sm font-medium text-white shadow-sm transition-all hover:border-primary-700 hover:bg-primary-700 focus:ring focus:ring-primary-200 disabled:cursor-not-allowed disabled:border-primary-300 disabled:bg-primary-300 dark:border-primary-500 dark:bg-primary-500 dark:hover:border-primary-400 dark:hover:bg-primary-400 dark:focus:ring-primary-700 dark:disabled:border-primary-800 dark:disabled:bg-primary-800">
                            Ver todos os planos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
