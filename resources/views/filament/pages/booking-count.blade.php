<x-filament-panels::page>
    <div style="--col-span-default: 1 / -1;"
        class="col-[--col-span-default] fi-wi-widget fi-wi-stats-overview grid gap-y-4">
        <div class="fi-wi-stats-overview-stats-ctn grid gap-6 md:grid-cols-3">
            @foreach ($resorts as $resort)
                <div
                    class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="grid gap-y-2">
                        <div class="flex items-center gap-x-2">
                            <span
                                class="fi-wi-stats-overview-stat-label text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $resort->name }} - Bookings
                            </span>
                        </div>

                        <div
                            class="fi-wi-stats-overview-stat-value text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ $resort->bookings_count }}
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-filament-panels::page>
