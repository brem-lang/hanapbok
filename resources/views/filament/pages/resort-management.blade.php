<x-filament-panels::page>
    {{ $this->form }}
    <div class="fi-ac gap-3 flex flex-wrap items-center justify-end">
        <x-filament::modal id="submit-modal" width="md" alignment="center" icon="heroicon-o-check" icon-color="success">
            <x-slot name="trigger">
                <x-filament::button x-cloak>
                    Save Changes
                </x-filament::button>
            </x-slot>
            <x-slot name="heading">
                Save Changes
            </x-slot>

            <x-slot name="description">
                Would you like to proceed?
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

    {{-- <div class="fi-resource-relation-managers flex flex-col gap-y-6">


        <nav class="fi-tabs flex max-w-full gap-x-1 overflow-x-auto mx-auto rounded-xl bg-white p-2 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
            role="tablist">
            <button type="button"
                class="fi-tabs-item group flex items-center justify-center gap-x-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 fi-active fi-tabs-item-active bg-gray-50 dark:bg-white/5"
                aria-selected="aria-selected" role="tab" wire:click="$set('activeRelationManager', '0')">
                <span class="fi-tabs-item-label transition duration-75 text-primary-600 dark:text-primary-400">
                    Entrance Fees
                </span>
            </button>
            <button type="button"
                class="fi-tabs-item group flex items-center justify-center gap-x-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5"
                role="tab">


                <span
                    class="fi-tabs-item-label transition duration-75 text-gray-500 group-hover:text-gray-700 group-focus-visible:text-gray-700 dark:text-gray-400 dark:group-hover:text-gray-200 dark:group-focus-visible:text-gray-200">
                    Items

                </span>




            </button>
        </nav>

    </div> --}}
    <div x-data="{ tab: 'entrance' }" class="w-full flex flex-col items-center">
        <!-- Tabs -->
        <div class="max-w-fit">
            <nav class="fi-tabs flex gap-x-1 overflow-x-auto mx-auto rounded-xl bg-white p-2 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
                role="tablist">

                <!-- Tab: Entrance Fees -->
                <button type="button" @click="tab = 'entrance'"
                    :class="tab === 'entrance'
                        ?
                        'bg-gray-50 dark:bg-white/5 text-primary-600 dark:text-primary-400' :
                        'hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 text-gray-500 dark:text-gray-400'"
                    class="fi-tabs-item group inline-flex items-center justify-center gap-x-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75"
                    role="tab">
                    <span class="fi-tabs-item-label transition duration-75">
                        Entrance Fees
                    </span>
                </button>

                <!-- Tab: Items -->
                <button type="button" @click="tab = 'items'"
                    :class="tab === 'items'
                        ?
                        'bg-gray-50 dark:bg-white/5 text-primary-600 dark:text-primary-400' :
                        'hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 text-gray-500 dark:text-gray-400'"
                    class="fi-tabs-item group inline-flex items-center justify-center gap-x-2 whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75"
                    role="tab">
                    <span class="fi-tabs-item-label transition duration-75">
                        Rooms/Cottages
                    </span>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-4 w-full" style="margin-top:25px;">
            <div x-show="tab === 'entrance'" x-cloak>
                {{-- <p>Entrance Fees content goes here.</p> --}}
                <livewire:entrance-fee-form />
            </div>
            <div x-show="tab === 'items'" x-cloak>
                {{-- <p>Items content goes here.</p> --}}
                <livewire:item />
            </div>
        </div>
    </div>



</x-filament-panels::page>
