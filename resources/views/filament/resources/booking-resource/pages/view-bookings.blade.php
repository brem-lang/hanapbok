<x-filament-panels::page>
    <h1 style="font-size: 20px;font-weight:bold">{{ $record->resort->name }}</h1>
    <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(3, minmax(0, 1fr)); margin-top: -25px;"
        class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6">
        <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
            <div>
                <div style="--cols-default: repeat(1, minmax(0, 1fr));"
                    class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">
                    <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
                        <section
                            class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                            <div class="fi-section-content-ctn">
                                <div class="fi-section-content p-6">
                                    <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(1, minmax(0, 1fr));"
                                        class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6">
                                        <div>
                                            <div>
                                                <img src="{{ asset('resorts-photo/' . $record->resort->image) }}"
                                                    alt="Image 1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <div style="--col-span-default: span 2 / span 2;" class="col-[--col-span-default]">
            <div>
                <div style="--cols-default: repeat(1, minmax(0, 1fr));"
                    class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">

                    <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
                        <section
                            class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                            <div class="fi-section-content-ctn p-6">
                                {{ $this->infoList }}
                                <br />
                                {{ $this->form }}
                                <br />

                                {{-- No Refund Policy Notice --}}
                                <div
                                    class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg mb-3">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">
                                                No Refund Policy
                                            </h3>
                                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                                <p>
                                                    Please note that all bookings are final and non-refundable. Once a
                                                    booking is confirmed, refunds will not be issued under any
                                                    circumstances, including but not limited to cancellation, changes in
                                                    plans, or no-shows. By confirming this booking, you acknowledge and
                                                    agree to this no refund policy.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    @if ($record->status == 'confirmed')
                                        <x-filament::button size="md" color="primary" disabled>
                                            Booking Confirm
                                        </x-filament::button>
                                    @else
                                        @if ($record->status != 'cancelled')
                                            <x-filament::modal id="confirm-modal" width="md" alignment="center"
                                                icon="heroicon-o-check" icon-color="success">
                                                <x-slot name="trigger">
                                                    <x-filament::button>
                                                        Confirm Booking
                                                    </x-filament::button>
                                                </x-slot>
                                                <x-slot name="heading">
                                                    Confirm Booking
                                                </x-slot>

                                                <x-slot name="description">
                                                    Would you like to proceed?
                                                </x-slot>

                                                <x-slot name="footerActions">
                                                    <x-filament::button size="md" color="primary" class="w-full"
                                                        wire:click.prevent="confirm">
                                                        Confirm
                                                    </x-filament::button>
                                                    <x-filament::button color="gray" outlined size="md"
                                                        class="w-full"
                                                        x-on:click.prevent="$dispatch('close-modal', {id: 'confirm-modal'})">
                                                        Cancel
                                                    </x-filament::button>
                                                </x-slot>
                                            </x-filament::modal>
                                        @endif

                                    @endif
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
