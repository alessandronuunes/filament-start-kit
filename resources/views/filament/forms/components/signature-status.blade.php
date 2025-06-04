{{-- Main subscription status view - Dispatcher for different states --}}
@php
    use App\Enums\SubscriptionStatus;
    use App\Models\Team;
    use function App\Support\tenant;

    $team = tenant(Team::class);

    // Livewire 3.x - get the parent component (Signature) through wireId
    $livewireComponent = null;
    if (function_exists('wire')) {
        $livewireComponent = \Livewire\Livewire::getInstance()->getComponent(wire()->id);
    }

    $currentStatus = $livewireComponent?->currentStatus ?? SubscriptionStatus::INACTIVE;

    // Determine which view to render based on status
    $viewToRender = match ($status) {
        SubscriptionStatus::FREE => 'filament.forms.components.free-plan-card',
        SubscriptionStatus::TRIAL => 'filament.forms.components.trial-plan-card',
        SubscriptionStatus::ACTIVE => 'filament.forms.components.active-plan-card',
        default => 'filament.forms.components.active-plan-card',
    };
@endphp

<div wire:key="signature-status-{{ \Illuminate\Support\Str::random(8) }}" class="w-full">
    @include($viewToRender, [
        'status' => $currentStatus,
        'trialEndsAt' => $livewireComponent?->trialEndsAt ?? null,
        'renewalDate' => $livewireComponent?->renewalDate ?? null,
        'currentPlan' => $livewireComponent?->currentPlan ?? null,
    ])
</div>
