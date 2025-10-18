<div
    style="width: 100vw; height: 100vh; margin: 0; font-family: 'Figtree', sans-serif; background-image: url('{{ asset('img/565800896_1564868748015658_5418465562393476186_n.jpg') }}'); background-size: cover; background-position: center; display: flex; justify-content: center; align-items: center;">
    <link rel="stylesheet" href="{{ asset('css/filament/filament/app.css?v=3.2.124.0') }}">
    <div style="width: 80%; background-color: rgba(255, 255, 255, 0.8); padding: 20px; border-radius: 8px;">
        <div class="text-center">
            {{ $this->form }}
            <div style="margin-top: 15px;" class="text-right">
                <x-filament::modal id="confirm-modal" width="md" alignment="center" icon="heroicon-o-check"
                    icon-color="success">
                    <x-slot name="trigger">
                        <div class="text-right">
                            <x-filament::button>
                                Upload
                            </x-filament::button>
                        </div>
                    </x-slot>
                    <x-slot name="heading">
                        Confirm ID Upload
                    </x-slot>

                    <x-slot name="description">
                        Are you sure you want to upload your ID for verification?
                    </x-slot>

                    <x-slot name="footerActions">
                        <x-filament::button size="md" color="primary" class="w-full"
                            wire:click.prevent="confirmID">
                            Confirm
                        </x-filament::button>
                        <x-filament::button color="gray" outlined size="md" class="w-full"
                            x-on:click.prevent="$dispatch('close-modal', {id: 'submit-modal'})">
                            Cancel
                        </x-filament::button>
                    </x-slot>
                </x-filament::modal>

                <x-filament::button color="gray" outlined wire:click.prevent="cancel">
                    Cancel
                </x-filament::button>
            </div>
        </div>
    </div>
</div>
