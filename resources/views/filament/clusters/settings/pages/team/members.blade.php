<x-filament-panels::page>
    <form wire:submit="create">
        {{ $this->form }}
    </form>
    <div class="flex flex-col gap-y-6" x-data="{ activeTab: @entangle('activeTab') }">
        <x-filament-panels::resources.tabs x-ref="tabsComponent" />

        @if ($activeTab === 'members')
            @livewire('team.list-members')
        @else
            @livewire('team.list-invitations')
        @endif
    </div>
</x-filament-panels::page>
