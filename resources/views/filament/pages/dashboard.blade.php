<x-filament-panels::page>
    @if (auth()->user()->isGuest())
        @if (!$record->is_validated)
            <h1 class="font-bold">To continue, upload a valid ID for account verification.</h1>

            @if ($record->notes)
                <x-filament::section class="mt-4 text-sm">
                    <div style="text-color:red;">
                        {{ $record->notes }}
                    </div>
                </x-filament::section>
            @endif

            <div style="margin-top: -15px;">
                {{ $this->userValidateIDForm }}
                <div class="text-right" style="margin-top: 15px;">
                    @if (!$this->record->front_id && !$this->record->back_id)
                        <x-filament::modal id="confirm-modal" width="md" alignment="center" icon="heroicon-o-check"
                            icon-color="success">
                            <x-slot name="trigger">
                                <x-filament::button>
                                    Upload
                                </x-filament::button>
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
                                    x-on:click.prevent="$dispatch('close-modal', {id: 'confirm-modal'})">
                                    Cancel
                                </x-filament::button>
                            </x-slot>
                        </x-filament::modal>
                    @else
                        @if ($record->status == 'rejected')
                            <x-filament::modal id="confirm-modal" width="md" alignment="center"
                                icon="heroicon-o-check" icon-color="success">
                                <x-slot name="trigger">
                                    <x-filament::button>
                                        Upload
                                    </x-filament::button>
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
                                        x-on:click.prevent="$dispatch('close-modal', {id: 'confirm-modal'})">
                                        Cancel
                                    </x-filament::button>
                                </x-slot>
                            </x-filament::modal>
                        @else
                            <x-filament::button size="md" color="primary" disabled>
                                Waitng for Confirmation
                            </x-filament::button>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    @else
    @endif
</x-filament-panels::page>
