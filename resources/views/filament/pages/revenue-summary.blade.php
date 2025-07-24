<x-filament-panels::page>
    <div>
        <div class="text-2xl">
            Welcome <span class="font-semibold">{{ auth()->user()->name }}</span>!
        </div>
        <div class="text">
            <p id="time"></p>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        {{-- This container now uses Filament's Button components --}}
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
        <div>
            {{-- Note: You will need to create the 'revenue.print' route and a PrintController --}}
            <a href="{{ route('revenue.print', ['filter' => $this->filter]) }}" target="_blank"
                class="inline-flex items-center gap-x-2 rounded-md bg-primary-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                <x-heroicon-s-printer class="-ml-0.5 h-5 w-5" />
                Print Report
            </a>
        </div>
    </div>

    {{-- Bar Graph --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6" wire:key="chart-{{ $this->filter }}"
        x-data="{
            chart: null,
            labels: @js($chartData['labels']),
            revenue: @js($chartData['revenue']),
            init() {
                // Destroy previous chart instance if it exists
                if (this.chart) {
                    this.chart.destroy();
                }
        
                this.chart = new Chart(this.$refs.canvas, {
                    type: 'bar',
                    data: {
                        labels: this.labels,
                        datasets: [{
                            label: 'Total Revenue',
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
                                beginAtZero: true
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
