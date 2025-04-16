<x-filament-panels::page>
    <div wire:loading wire:target="getResorts" class="flex w-full items-center justify-center px-2 py-4">
        <span>Loading...</span>
    </div>
    <div x-data="{ resorts: @entangle('resorts').live, imagePath: '{{ asset('resorts-photo') }}' }" x-init="$wire.getResorts()" wire:loading.remove wire:target="getResorts">
        <template x-if="resorts.length > 0">
            <template x-for="data in resorts" :key="data.id">
                <div style="margin-bottom: 30px;">
                    {{-- <h1 class="text-xl font-bold mb-2" x-text="data.name"></h1>
                    <x-filament::section>
                        <p class="text-sm text-gray-600">Test content for: <span x-text="data.name"></span></p>
                    </x-filament::section> --}}
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
    </div>

</x-filament-panels::page>
