{{-- View para plano ativo --}}
@php
    // Obtém os dados do plano
    $currentPlan = $plan ?? null;
    $planName = $currentPlan['name'] ?? 'Plano Atual';
    $planDescription = $currentPlan['description'] ?? '';
    $planPrice = $currentPlan['price'] ?? 0;
    $planPeriod = $currentPlan['period'] ?? 'monthly';

    $defaultDescription = 'Este plano oferece todos os recursos necessários para otimizar seu fluxo de trabalho e melhorar sua produtividade.';
    // Formata o preço
    $formattedPrice = number_format($planPrice / 100, 2, ',', '.');

    // Tradução do período
    $periodLabel = [
        'monthly' => 'Mensal',
        'yearly' => 'Anual',
    ][$planPeriod] ?? $planPeriod;

    // Define os recursos do plano
    $planFeatures = $plan['features'] ?? [];
@endphp

<div class="w-full">
    <div class="rounded-xl overflow-hidden border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-900">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">{{ $planName }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $planDescription ?: $defaultDescription }}</p>
                </div>
                <div class="rounded-lg bg-success-50 px-3 py-1 text-sm font-medium text-success-700 dark:bg-success-900/50 dark:text-success-400">
                    Ativo
                </div>
            </div>
        </div>

        <div class="">
            <div class="mx-auto max-w-2xl rounded-3xl lg:mx-0 lg:flex lg:max-w-none">
                <div class="p-8 sm:p-10 lg:flex-auto">
                     <div class="mt-2 flex items-center gap-x-4">
                        <h4 class="flex-none text-sm/6 font-semibold text-primary-600 dark:text-primary-500">Recursos incluídos</h4>

                    </div>
                    <ul role="list" class="mt-8 grid grid-cols-1 gap-4 text-sm/6 text-gray-600 dark:text-gray-300 sm:grid-cols-2 sm:gap-6">
                        @foreach($planFeatures as $feature)
                            <li class="flex gap-x-3">
                                <svg class="h-6 w-5 flex-none text-primary-600 dark:text-primary-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="-mt-2 p-2 lg:mt-0 lg:w-full lg:max-w-md lg:shrink-0 dark:bg-gray-800">
                    <div class=" text-center lg:flex lg:flex-col lg:justify-center lg:py-16 ">
                        <div class="mx-auto max-w-xs px-4">
                            <p class="text-base font-semibold text-gray-600 dark:text-gray-300">{{ $periodLabel === 'Mensal' ? 'Assinatura mensal' : 'Assinatura anual' }}</p>
                            <p class="mt-6 flex items-baseline justify-center gap-x-2">
                                <span class="text-4xl font-semibold tracking-tight text-gray-900 dark:text-white">R$ {{ $formattedPrice }}</span>
                                <span class="text-sm/6 font-semibold tracking-wide text-gray-600 dark:text-gray-300">{{ $periodLabel }}</span>
                            </p>
                            <div class="mt-10 space-y-4">
                                <a href="#"
                                   wire:click="redirectBilling"
                                   class="block w-full rounded-md bg-primary-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                    Gerenciar assinatura
                                </a>
                            </div>
                            <div class="mt-6 space-y-4">
                                <div class="text-xs/5 text-gray-600 dark:text-gray-400">
                                    Próxima renovação: {{ $renewDate ?? 'Não disponível' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
