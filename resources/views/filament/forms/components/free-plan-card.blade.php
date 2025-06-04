{{-- View para plano gratuito --}}
<div class="w-full">
    <div class="rounded-xl overflow-hidden border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">Plano Gratuito</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Acesso especial sem custos</p>
                </div>
                <div class="rounded-lg bg-success-50 px-3 py-1 text-sm font-medium text-success-700 dark:bg-success-900/50 dark:text-success-400">
                    Ativo
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Coluna esquerda - Benefícios -->
            <div class="p-6 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700">
                <div class="mb-4">
                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Benefícios incluídos</h4>
                </div>

                <ul class="space-y-4">
                    <li class="flex gap-x-3">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-success-500 flex items-center justify-center">
                            <x-heroicon-s-check class="h-3 w-3 text-white" />
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Acesso básico ao sistema</span>
                    </li>
                    <li class="flex gap-x-3">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-success-500 flex items-center justify-center">
                            <x-heroicon-s-check class="h-3 w-3 text-white" />
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Suporte por email</span>
                    </li>
                    <li class="flex gap-x-3">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-success-500 flex items-center justify-center">
                            <x-heroicon-s-check class="h-3 w-3 text-white" />
                        </span>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Recursos essenciais</span>
                    </li>
                </ul>
            </div>

            <!-- Coluna direita - Mensagem especial -->
            <div class="p-6">
                <div class="flex flex-col h-full">
                    <div class="mb-6">
                        <div class="rounded-lg bg-success-50 p-4 dark:bg-success-900/20">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <x-heroicon-s-gift class="h-6 w-6 text-success-600 dark:text-success-400" />
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-success-800 dark:text-success-300">Acesso exclusivo</h3>
                                    <div class="mt-2 text-sm text-success-700 dark:text-success-400">
                                        <p>
                                            Sua equipe é muito importante para nós. Por isso, oferecemos acesso gratuito a nossa plataforma.
                                            Aproveite todos os recursos essenciais sem custo.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white mb-2">Precisa de mais recursos?</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            Caso sua equipe precise de recursos avançados, você pode fazer o upgrade a qualquer momento.
                        </p>

                        <button
                            type="button"
                            onclick="Livewire.dispatch('signature-redirect-to-subscribe')"
                            class="w-full rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-100 focus:ring focus:ring-gray-200 disabled:cursor-not-allowed disabled:border-gray-300 disabled:bg-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                            Ver planos disponíveis
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
