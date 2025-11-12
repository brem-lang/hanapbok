<x-filament-panels::page>
    {{ $this->form }}

    <div class="fi-ac gap-3 flex flex-wrap items-center justify-start">
        <x-filament::modal id="submit-modal" width="md" alignment="center" icon="heroicon-o-check" icon-color="success">
            <x-slot name="trigger">
                <x-filament::button x-cloak>
                    Create
                </x-filament::button>
            </x-slot>
            <x-slot name="heading">
                Submit
            </x-slot>

            <x-slot name="description">
                Are you sure you would like to do this?
            </x-slot>
            <x-slot name="footerActions">
                <x-filament::button size="md" color="primary" class="w-full" wire:click.prevent="submit">
                    Confirm
                </x-filament::button>
                <x-filament::button color="gray" outlined size="md" class="w-full"
                    x-on:click.prevent="$dispatch('close-modal', {id: 'submit-modal'})">
                    Cancel
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    </div>
</x-filament-panels::page>
