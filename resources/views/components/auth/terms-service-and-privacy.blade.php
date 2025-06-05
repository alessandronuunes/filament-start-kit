<p class="text-center mt-3 text-sm">
    Ao clicar em Login, você concorda com nossos <br />

    <button
        x-on:click="$dispatch('open-modal', {id: 'terms'})"
        class="font-semibold text-primary-400 hover:underline focus-visible:underline transition-colors duration-200"
    >
        Termos de Serviço
    </button> e
    <button
        x-on:click="$dispatch('open-modal', {id: 'privacy'})"
        class="font-semibold text-primary-400 hover:underline focus-visible:underline transition-colors duration-200"
    >
        Políticas de Privacidade
    </button>
</p>
<x-filament::modal id="terms" width="2xl" sticky-header="true">
    {{-- Modal content --}}
    <x-slot name="heading">
        Termos de Serviço
    </x-slot>
    <x-markdown-document path="legal/terms-of-service" />
</x-filament::modal>
<x-filament::modal id="privacy" width="2xl" sticky-header="true">
    {{-- Modal content --}}
    <x-slot name="heading">
        Políticas de Privacidade
    </x-slot>
    <x-markdown-document path="legal/privacy-policy" />
</x-filament::modal>
