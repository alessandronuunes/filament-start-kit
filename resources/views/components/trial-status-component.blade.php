@php
    use App\Helpers\Cashier\TrialManager;
    use App\Models\Team;
    use function App\Support\tenant;

    $team = tenant(Team::class);
    $isOnTrial = TrialManager::isOnTrial($team);
    $daysLeft = TrialManager::daysLeft($team);
@endphp
@if($isOnTrial)
    <div class="p-4 bg-warning-50 dark:bg-warning-900/50">
        <div class="flex justify-center">
            <div class="shrink-0">
                <svg class="size-5 text-warning-700 dark:text-warning-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-warning-700 dark:text-warning-400">
                    Período de teste: {{ $daysLeft }} {{ $daysLeft === 1 ? 'dia' : 'dias' }} restantes.
                    <button
                        x-data
                        x-on:click="window.location.href = '{{ route('filament.admin.pages.dashboard', ['tenant' => $team->slug, 'action' => 'subscribe']) }}'"
                        class="font-medium text-warning-700 dark:text-warning-400 underline hover:text-warning-600 dark:hover:text-warning-300"
                    >
                        Assinar agora para continuar usando todos os recursos.
                    </button>
                </p>
            </div>
        </div>
    </div>
@endif
