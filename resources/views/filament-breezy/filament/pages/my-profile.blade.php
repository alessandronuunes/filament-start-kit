<x-filament::page x-data="{ activeTab: 'profile' }">
    <x-filament::tabs contained>
        <x-filament::tabs.item alpine-active="activeTab === 'profile'" x-on:click="activeTab = 'profile'">
            Perfil
        </x-filament::tabs.item>

        <x-filament::tabs.item alpine-active="activeTab === 'security'" x-on:click="activeTab = 'security'">
            Segurança
        </x-filament::tabs.item>


    </x-filament::tabs>

    <div class="space-y-6 divide-y divide-gray-900/10 dark:divide-white/10" x-show="activeTab === 'profile'">
        @foreach ($this->getProfileComponents() as $component)
            @unless (is_null($component))
                @livewire($component)
            @endunless
        @endforeach
    </div>

    <div class="space-y-6 divide-y divide-gray-900/10 dark:divide-white/10" x-show="activeTab === 'security'">
        @foreach ($this->getSecurityComponents() as $component)
            @unless (is_null($component))
                @livewire($component)
            @endunless
        @endforeach
    </div>

    <div class="space-y-6 divide-y divide-gray-900/10 dark:divide-white/10" x-show="activeTab === 'signature'">
{{--        @foreach ($this->getSignatureComponents() as $component)--}}
{{--            @unless (is_null($component))--}}
{{--                @livewire($component)--}}
{{--            @endunless--}}
{{--        @endforeach--}}

    </div>
</x-filament::page>
