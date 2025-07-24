<x-filament-panels::page>
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

    {{-- @livewire(\App\Livewire\DashboardChart::class) --}}
    {{-- Filters and Print Button --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-x-2">
            <x-filament::button wire:click="setFilter('daily')" :color="$this->filter === 'daily' ? 'primary' : 'gray'">
                Daily
            </x-filament::button>

            <x-filament::button wire:click="setFilter('monthly')" :color="$this->filter === 'monthly' ? 'primary' : 'gray'">
                Monthly
            </x-filament::button>

            <x-filament::button wire:click="setFilter('yearly')" :color="$this->filter === 'yearly' ? 'primary' : 'gray'">
                Yearly
            </x-filament::button>
        </div>
        {{-- <div>
            <a href="{{ route('bookings.print', ['filter' => $this->filter]) }}" target="_blank"
                class="inline-flex items-center gap-x-2 rounded-md bg-gray-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                <x-heroicon-s-printer class="-ml-0.5 h-5 w-5" />
                Print Report
            </a>
        </div> --}}
    </div>

    {{-- Bar Graph --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6" wire:key="chart-{{ $this->filter }}"
        x-data="{
            chart: null,
            labels: @js($chartData['labels']),
            revenue: @js($chartData['datasets'][0]['data']), // Correctly access the data array
            init() {
                if (this.chart) {
                    this.chart.destroy();
                }
                this.chart = new Chart(this.$refs.canvas, {
                    type: 'bar',
                    data: {
                        labels: this.labels,
                        datasets: [{
                            label: 'Total Bookings',
                            data: this.revenue,
                            backgroundColor: 'rgba(79, 70, 229, 0.8)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1 // Ensure y-axis increments by whole numbers
                                }
                            }
                        }
                    }
                });
            }
        }">
        <div style="height: 600px">
            <canvas x-ref="canvas"></canvas>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
</x-filament-panels::page>
<script>
    function startTime() {
        const today = new Date();
        document.getElementById('time').innerHTML = 'Today is ' + today;
        setTimeout(startTime, 1000);
    }
    startTime()
</script>
