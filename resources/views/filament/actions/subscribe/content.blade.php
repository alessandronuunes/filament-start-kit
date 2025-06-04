@php
    use App\Helpers\Cashier\Stripe;
    use App\Helpers\Cashier\Plan;

    $stripe = Stripe::fromConfig();
    $plans = $stripe->plans();
    $selectedPlanType = $selectedPlan ?? 'default';
    $billedPeriods = $stripe->billedPeriods();
    $trialDays = $stripe->trialDays();
@endphp

{{-- Header do Modal --}}
<div class="mx-auto max-w-4xl text-center">
    @if($brandLogo)
        <div class="flex justify-center mb-4">
            <img src="{{ $brandLogo }}" alt="Logo" class="h-12">
        </div>
    @endif

    <h2 class="text-base font-semibold text-primary-600 dark:text-primary-400">{{ __('Nossos Planos') }}</h2>
    <p class="mt-2 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
        {{ __('Escolha o plano ideal para você') }}
    </p>
</div>

{{-- Alpine.js data para todo o componente --}}
<div x-data="{
    period: 'monthly',
    selectedPriceId: '',
    isLoading: false,

    // Atualiza o período e notifica outros componentes
    setPeriod(value) {
        if (this.isLoading) return; // Evita mudanças durante o carregamento

        this.period = value;
        this.$dispatch('period-changed', value);
        // Atualiza o ID do preço quando o período muda
        if (this.selectedPlanType) {
            this.selectedPriceId = this.getPriceId(this.selectedPlanType, this.period);
            this.updateHiddenField();
        }
    },

    // Armazena o tipo de plano selecionado
    selectedPlanType: '{{ $selectedPlanType }}',

    // Seleciona um plano e atualiza o ID do preço
    selectPlan(planType) {
        if (this.isLoading) return; // Evita mudanças durante o carregamento

        this.selectedPlanType = planType;
        this.selectedPriceId = this.getPriceId(planType, this.period);
        this.updateHiddenField();
    },

    // Recupera o ID do preço com base no tipo de plano e período
    getPriceId(planType, period) {
        const prices = {
            @foreach($plans as $plan)
                '{{ $plan->type() }}': {
                    @foreach($plan->prices() as $price)
                        '{{ $price->period() }}': '{{ $price->id() }}',
                    @endforeach
                },
            @endforeach
        };
        return prices[planType]?.[period] || '';
    },

    // Atualiza o campo hidden para o Livewire
    updateHiddenField() {
        this.$wire.set('mountedActionsData.subscribe.billing_period', this.selectedPriceId);
    },

    // Formata o preço para exibição
    formatPrice(price) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(price / 100);
    },

    // Inicia o checkout
    startCheckout() {
        if (this.isLoading) return; // Previne múltiplos cliques

        // Ativa o estado de carregamento
        this.isLoading = true;

        // Certifica-se de que o selectedPriceId está definido novamente
        this.updateHiddenField();

        // Exibe mensagem de carregamento
        this.$dispatch('notify', {
            message: '{{ __('Preparando seu checkout. Por favor, aguarde...') }}',
            type: 'info'
        });

        // Método direto para chamar a ação no Filament
        this.$nextTick(() => {
            // Define um timeout de segurança (10 segundos)
            setTimeout(() => {
                if (this.isLoading) {
                    this.isLoading = false; // Reseta o estado se demorar demais
                    this.$dispatch('notify', {
                        message: '{{ __('Parece que estamos tendo problemas. Tente novamente.') }}',
                        type: 'error'
                    });
                }
            }, 10000);

            // Usa o mountAction diretamente
            this.$wire.mountAction('checkout', { billing_period: this.selectedPriceId });
        });
    }
}" x-init="
    // Inicializa o ID do preço na carga da página
    selectedPriceId = getPriceId(selectedPlanType, period);
    updateHiddenField();

    // Configura listener para notificações
    $watch('isLoading', value => {
        if (value) {
            document.body.classList.add('cursor-wait');
        } else {
            document.body.classList.remove('cursor-wait');
        }
    });
" x-on:period-changed.window="period = $event.detail">

    {{-- Campo hidden para armazenar o ID do preço selecionado --}}
    <input
        type="hidden"
        id="billing_period_field"
        wire:model="mountedActionsData.subscribe.billing_period"
    >

    {{-- Seletor de Período --}}
    <div class="mt-6 flex justify-center">
        <fieldset aria-label="{{ __('Frequência de pagamento') }}">
            <div class="grid grid-cols-2 gap-x-1 rounded-full p-1 text-center text-xs font-semibold ring-1 ring-inset ring-gray-200 dark:ring-gray-700">
                @foreach($billedPeriods as $periodKey => $periodValue)
                    <label
                        x-bind:class="period === '{{ $periodKey }}' ? 'bg-primary-600 text-white dark:bg-primary-500' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
                        class="cursor-pointer rounded-full px-2.5 py-1.5 transition-colors"
                    >
                        <input
                            type="radio"
                            name="period"
                            value="{{ $periodKey }}"
                            class="sr-only"
                            x-bind:checked="period === '{{ $periodKey }}'"
                            x-on:change="setPeriod('{{ $periodKey }}')"
                        >
                        <span>{{ $periodValue }}</span>
                    </label>
                @endforeach
            </div>
        </fieldset>
    </div>

    {{-- Cards de Planos --}}
    <div class="isolate mx-auto mt-10 grid grid-cols-1 gap-8 lg:mx-0 md:grid-cols-2 justify-center w-full md:px-10">
        <div class="md:col-span-2 flex justify-center w-full">
            <div class="grid md:grid-cols-2 gap-8 w-full max-w-4xl">
                @foreach($plans as $plan)
                    @php
                        $isPlanSelected = $selectedPlanType === $plan->type();
                    @endphp
                    <div class="rounded-3xl p-8 w-full"
                         x-bind:class="selectedPlanType === '{{ $plan->type() }}' ?
                            'ring-2 ring-primary-600 dark:ring-primary-500' :
                            'ring-1 ring-gray-200 dark:ring-gray-700'">

                        {{-- Conteúdo do card --}}
                        <div class="flex items-center justify-between gap-x-4">
                            <h3 id="tier-{{ $plan->type() }}" class="text-lg font-semibold"
                                x-bind:class="selectedPlanType === '{{ $plan->type() }}' ?
                                    'text-primary-600 dark:text-primary-400' :
                                    'text-gray-900 dark:text-white'">
                                {{ $plan->name() }}
                            </h3>
                            <p x-show="selectedPlanType === '{{ $plan->type() }}'"
                               class="rounded-full bg-primary-600/10 dark:bg-primary-400/10 px-2.5 py-1 text-xs font-semibold text-primary-600 dark:text-primary-400">
                                {{ __('Mais popular') }}
                            </p>
                        </div>

                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">{{ $plan->shortDescription() }}</p>

                        {{-- Preço dinâmico baseado no período selecionado --}}
                        @foreach($plan->prices() as $price)
                            <div x-bind:class="{ 'hidden': period !== '{{ $price->period() }}' }" x-transition class="mt-6 flex items-baseline gap-x-1">
                                <span class="text-4xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    <span x-text="formatPrice({{ $price->price() }})"></span>
                                </span>
                                <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                                    /{{ $billedPeriods[$price->period()] }}
                                </span>
                            </div>
                        @endforeach

                        {{-- Botão de Compra --}}
                        <button type="button"
                                x-on:click="selectPlan('{{ $plan->type() }}'); startCheckout()"
                                aria-describedby="tier-{{ $plan->type() }}"
                                x-bind:disabled="isLoading"
                                class="mt-6 block w-full rounded-md relative"
                                x-bind:class="[
                                    isLoading ? 'opacity-75 cursor-not-allowed' : '',
                                    selectedPlanType === '{{ $plan->type() }}' ?
                                        'bg-primary-600 dark:bg-primary-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-primary-500 dark:hover:bg-primary-400' :
                                        'px-3 py-2 text-center text-sm font-semibold text-primary-600 dark:text-primary-400 ring-1 ring-inset ring-primary-200 dark:ring-primary-800 hover:ring-primary-300 dark:hover:ring-primary-700'
                                ]">
                            <span x-show="!isLoading">{{ __('Assinar plano') }}</span>
                            <span x-show="isLoading" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('Processando...') }}
                            </span>
                        </button>

                        {{-- Lista de Recursos --}}
                        <ul role="list" class="mt-8 space-y-3 text-sm text-gray-600 dark:text-gray-400">
                            @foreach($plan->features() as $feature)
                                <li class="flex gap-x-3">
                                    <x-heroicon-m-check class="h-5 w-5 flex-none text-primary-600 dark:text-primary-400" />
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Texto de rodapé --}}
    <p class="mx-auto mt-4 max-w-2xl text-center text-md text-gray-600 dark:text-gray-400">
        {{ __('Selecione um plano acessível que inclui os melhores recursos para o seu negócio.') }}
        <br />
    </p>

    {{-- Overlay de carregamento global --}}
    <div
        x-show="isLoading"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl max-w-md w-full text-center">
            <div class="animate-spin mx-auto h-12 w-12 text-primary-600 dark:text-primary-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('Redirecionando para o checkout') }}</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Você será redirecionado para a página de pagamento do Stripe em instantes.') }}</p>
        </div>
    </div>
</div>
