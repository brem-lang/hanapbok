<x-filament-panels::page>
    {{-- @if (auth()->user()->isGuest())
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
        @endif --}}
    {{-- <div wire:loading wire:target="getResorts" class="flex w-full items-center justify-center px-2 py-4">
        <span>Loading...</span>
    </div>
    <div x-data="{ resorts: @entangle('resorts').live, imagePath: '{{ asset('resorts-photo') }}' }" x-init="$wire.getResorts()" wire:loading.remove wire:target="getResorts">
        <template x-if="resorts.length > 0">
            <template x-for="data in resorts" :key="data.id">
                <div style="margin-bottom: 30px;">
                    <h1 class="text-xl font-bold mb-2" x-text="data.name"></h1>
                    <x-filament::section>
                        <p class="text-sm text-gray-600">Test content for: <span x-text="data.name"></span></p>
                    </x-filament::section>
                    <div>
                        <h1 class="text-xl font-bold mb-2" x-text="data.name"></h1>
                    </div>
                    <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(3, minmax(0, 1fr)); margin-top: 7px;"
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
                                                                <img :src="`${imagePath}/${resort.image}`"
                                                                    alt="Resort Image"
                                                                    class="w-full max-w-md rounded-lg shadow"
                                                                    x-show="resort.image">
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
                                            <div class="fi-section-content-ctn">
                                                <div class="fi-section-content p-6">
                                                    <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(2, minmax(0, 1fr));"
                                                        class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6">

                                                        <div style="font-size: 14px; display: flex;width: 100%;">
                                                            <div class="column"
                                                                style="padding: 0 10px; word-break: break-word;">
                                                                <h1>Rates</h1>
                                                                <ul class="features-list"
                                                                    style="list-style-type: none; padding: 0;">
                                                                    <li>₱ 400.00 - 3 hours stay</li>
                                                                    <li>₱ 600.00 - 6 hours stay</li>
                                                                    <li>₱ 900.00 - 12 hours stay</li>
                                                                    <li>₱ 1600.00 - Overnight stay</li>
                                                                    <li>₱ 150.00 - Extension / hour</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div style="font-size: 14px; display: flex;width: 100%;">
                                                            <div class="column"
                                                                style="flex: auto; padding: 0 10px; word-break: break-word;">
                                                                <h1>Amenities</h1>
                                                                <ul class="features-list"
                                                                    style="list-style-type: none; padding: 0;">
                                                                    <li><i class="fas fa-check-circle"></i>
                                                                        Airconditioned Room</li>
                                                                    <li><i class="fas fa-check-circle"></i> Essential
                                                                        Kit</li>
                                                                    <li><i class="fas fa-check-circle"></i>
                                                                        Complimentary Bottled Water
                                                                    </li>
                                                                    <li><i class="fas fa-check-circle"></i> Parking
                                                                        space</li>
                                                                    <li><i class="fas fa-check-circle"></i> Fire Alarm
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="column"
                                                                style="flex: auto; padding: 0 10px; word-break: break-word;">

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
            </template>
        </template>
    </div> --}}
    {{-- @else
    @endif --}}
    <div>
        <div class="text-2xl">
            Welcome <span class="font-semibold">{{ auth()->user()->name }}</span>!
        </div>
        <div class="text">
            <p id="time"></p>
        </div>
    </div>
    @if (auth()->user()->isAdmin())
        @livewire(\App\Livewire\StatsOverview::class)
    @endif

    @if (auth()->user()->isResortsAdmin())
        @livewire(\App\Livewire\StatsOverviewResortAdmin::class)
    @endif

    @livewire(\App\Livewire\DashboardChart::class)

</x-filament-panels::page>
<script>
    function startTime() {
        const today = new Date();
        document.getElementById('time').innerHTML = 'Today is ' + today;
        setTimeout(startTime, 1000);
    }
    startTime()
</script>
