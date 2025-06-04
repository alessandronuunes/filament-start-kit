<div
    class="min-h-screen bg-gray-50 py-24 dark:bg-zinc-950"
    x-data="{
        period: 'monthly',
        config: {{ json_encode(config('stripe')) }},
        formatPrice(amount) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(amount / 100);
        }
    }"
>
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 md:text-5xl dark:text-gray-50"> {{ $this->getHeading() }}</h1>
            <p class="mt-4 text-base text-gray-700 md:text-lg dark:text-gray-300">
                {{ $this->getSubheading() }}
                @if($this->hasDiscount())
                    <span class="text-primary-600 dark:text-primary-400 font-semibold">Economize até {{ $this->getDiscount() }}%</span>
                @endif

            </p>
        </div>

        <div class="mt-10 flex justify-center">
            <div class="relative flex rounded-full bg-gray-100/80 p-1.5 backdrop-blur-sm dark:bg-gray-800/50">
                <button
                    @click="period = 'monthly'"
                    :class="{
               'bg-white dark:bg-gray-900 shadow-lg ring-1 ring-black/5 dark:ring-white/10': period === 'monthly',
               'hover:bg-gray-50 dark:hover:bg-gray-800/80': period !== 'monthly'
           }"
                    class="relative rounded-full px-5 py-2.5 text-sm font-medium text-gray-700 transition-all duration-300 ease-in-out dark:text-gray-200"
                >
                    {{ __('Mensal') }}
                </button>
                <button
                    @click="period = 'yearly'"
                    :class="{
                           'bg-white dark:bg-gray-900 shadow-lg ring-1 ring-black/5 dark:ring-white/10': period === 'yearly',
                           'hover:bg-gray-50 dark:hover:bg-gray-800/80': period !== 'yearly'
                       }"
                    class="relative rounded-full px-5 py-2.5 text-sm font-medium text-gray-700 transition-all duration-300 ease-in-out dark:text-gray-200"
                >
                    <span class="flex items-center gap-2">
                        {{ __('Anual') }}
                        @if($this->hasDiscount())
                            <span class="bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 ring-primary-500/20 dark:ring-primary-500/30 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1"> Economize {{ $this->getDiscount() }}% </span>
                        @endif
                    </span>
                </button>
            </div>
        </div>

        <div class="mt-12 flex justify-center gap-4">
            <template x-for="(plan, key) in config.plans" :key="key">
                <div
                    :class="{
                        'ring-2 ring-primary-500/20 dark:ring-primary-500/30': plan.popular
                    }"
                    class="group relative flex w-[360px] flex-col justify-between rounded-3xl bg-white p-8 backdrop-blur-xl transition-all duration-300 hover:-translate-y-1 dark:bg-gray-900/95"
                >
                    <div x-show="plan.popular" class="from-primary-500/10 absolute inset-0 -z-10 rounded-3xl bg-gradient-to-b to-transparent blur-2xl"></div>

                    <div x-show="plan.popular" class="absolute -top-4 right-0 left-0 mx-auto w-fit">
                        <span class="bg-primary-500/10 text-primary-600 dark:text-primary-400 ring-primary-500/20 inline-flex items-center rounded-full px-3 py-1 text-xs font-medium ring-1 ring-inset"> {{ __('Popular') }} </span>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <h3 x-text="plan.name" class="text-xl font-semibold text-gray-900 dark:text-white"></h3>
                            <span x-text="plan.short_description" class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs text-gray-700 dark:bg-gray-800 dark:text-gray-300"> </span>
                        </div>

                        <p class="mt-8 flex items-baseline gap-x-1">
                            <span x-text="formatPrice(plan.prices[period].price)" class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white"> </span>
                            <span x-show="period === 'monthly'" class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('/mês') }}
                            </span>
                            <span x-show="period === 'yearly'" class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('/ano') }}
                            </span>
                        </p>

                        <div class="mt-8">
                            <p class="text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-500">{{ __('O QUE ESTÁ INCLUSO') }}</p>
                            <ul role="list" class="mt-4 space-y-3.5">
                                <template x-for="(feature, index) in plan.features" :key="index">
                                    <li class="flex gap-x-3">
                                        <svg class="text-primary-600 dark:text-primary-400 h-5 w-4 flex-none" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="feature" class="text-sm text-gray-600 dark:text-gray-300"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <button wire:click="mountAction('checkoutAction', { priceId: plan.prices[period].id })" :class="plan.popular ? 'bg-primary-500 hover:bg-primary-600 text-white' : 'bg-white hover:bg-gray-100 text-gray-900 dark:bg-white dark:hover:bg-gray-100 dark:text-gray-900'" class="mt-8 block w-full rounded-xl px-4 py-3 text-sm font-medium shadow-sm transition-colors duration-200">
                        {{ __('Assinar') }}
                    </button>
                </div>
            </template>
        </div>
    </div>
</div>

