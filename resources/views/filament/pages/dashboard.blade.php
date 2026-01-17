<x-filament-panels::page>
    <div>
        <div class="text-2xl mb-4">
            Welcome <span class="font-semibold">{{ auth()->user()->name }}</span>!
        </div>
        <div class="text mb-6">
            <p id="time"></p>
        </div>
    </div>

    @if (auth()->user()->isAdmin())
        @livewire(\App\Livewire\StatsOverview::class)

        {{-- Filter Buttons --}}
        <div class="mb-4 md:mb-6 flex flex-wrap gap-2">
            <button
                wire:click="setFilter('weekly')"
                class="px-3 py-2 md:px-4 md:py-2 text-sm md:text-base rounded-lg font-medium transition-colors {{ $this->filter === 'weekly' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                Weekly
            </button>
            <button
                wire:click="setFilter('monthly')"
                class="px-3 py-2 md:px-4 md:py-2 text-sm md:text-base rounded-lg font-medium transition-colors {{ $this->filter === 'monthly' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                Monthly
            </button>
            <button
                wire:click="setFilter('annually')"
                class="px-3 py-2 md:px-4 md:py-2 text-sm md:text-base rounded-lg font-medium transition-colors {{ $this->filter === 'annually' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                Annually
            </button>
        </div>

        {{-- Top Performing Resorts Section --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 md:p-6">
            <h2 class="text-lg md:text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Top Performing Resorts</h2>
            <div class="overflow-x-auto -mx-4 md:mx-0">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="w-16 text-left py-3 px-4 text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Rank</th>
                            <th class="text-left py-3 px-4 text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Resort Name</th>
                            <th class="w-32 text-right py-3 px-4 text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Total Bookings</th>
                            <th class="w-40 text-right py-3 px-4 text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Total Sales</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($this->topResorts as $index => $resort)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">#{{ $index + 1 }}</td>
                                <td class="py-3 px-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $resort['name'] }}</td>
                                <td class="py-3 px-4 whitespace-nowrap text-sm text-right text-gray-700 dark:text-gray-300">{{ number_format($resort['total_bookings']) }}</td>
                                <td class="py-3 px-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-gray-100">₱ {{ number_format($resort['total_sales'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">No resort data available for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
            {{-- Bookings Over Time Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 md:p-6" wire:key="bookings-chart-{{ $this->filter }}">
                <h3 class="text-base md:text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Bookings Over Time</h3>
                <div style="height: 300px; position: relative;" class="w-full" 
                     x-data="chartData(@js($this->bookingsOverTime['labels'] ?? []), @js($this->bookingsOverTime['datasets'][0]['data'] ?? []), 'line', 'bookingsChart')"
                     x-init="initChart()">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            {{-- Sales Over Time Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 md:p-6" wire:key="sales-chart-{{ $this->filter }}">
                <h3 class="text-base md:text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Sales Over Time</h3>
                <div style="height: 300px; position: relative;" class="w-full"
                     x-data="chartData(@js($this->salesOverTime['labels'] ?? []), @js($this->salesOverTime['datasets'][0]['data'] ?? []), 'line', 'salesChart')"
                     x-init="initChart()">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Resorts Performance Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 md:p-6 mb-6" wire:key="top-resorts-chart-{{ $this->filter }}">
            <h3 class="text-base md:text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Top Resorts Performance</h3>
            <div style="height: 400px; position: relative;" class="w-full"
                 x-data="barChartData(@js($this->topResortsChart['labels'] ?? []), @js($this->topResortsChart['datasets'][0]['data'] ?? []), @js($this->topResortsChart['datasets'][1]['data'] ?? []))"
                 x-init="initChart()">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>
    @endif

    @if (auth()->user()->isResortsAdmin())
        @livewire(\App\Livewire\StatsOverviewResortAdmin::class)

        {{-- Filter Buttons for Resort Admin --}}
        <div class="mb-4 md:mb-6 flex flex-wrap gap-2">
            <button
                wire:click="setResortAdminFilter('weekly')"
                class="px-3 py-2 md:px-4 md:py-2 text-sm md:text-base rounded-lg font-medium transition-colors {{ $this->resortAdminFilter === 'weekly' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                Weekly
            </button>
            <button
                wire:click="setResortAdminFilter('monthly')"
                class="px-3 py-2 md:px-4 md:py-2 text-sm md:text-base rounded-lg font-medium transition-colors {{ $this->resortAdminFilter === 'monthly' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                Monthly
            </button>
            <button
                wire:click="setResortAdminFilter('annually')"
                class="px-3 py-2 md:px-4 md:py-2 text-sm md:text-base rounded-lg font-medium transition-colors {{ $this->resortAdminFilter === 'annually' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                Annually
            </button>
        </div>

        {{-- Charts Section for Resort Admin --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
            {{-- Sales Over Time Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 md:p-6" wire:key="resort-admin-sales-chart-{{ $this->resortAdminFilter }}">
                <h3 class="text-base md:text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Sales Over Time</h3>
                <div style="height: 300px; position: relative;" class="w-full" 
                     x-data="chartData(@js($this->salesOverTimeResortAdmin['labels'] ?? []), @js($this->salesOverTimeResortAdmin['datasets'][0]['data'] ?? []), 'line', 'resortSalesChart')"
                     x-init="initChart()">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>

            {{-- Booking Count Over Time Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 md:p-6" wire:key="resort-admin-bookings-chart-{{ $this->resortAdminFilter }}">
                <h3 class="text-base md:text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Booking Count Over Time</h3>
                <div style="height: 300px; position: relative;" class="w-full"
                     x-data="barChartDataResortAdmin(@js($this->bookingCountOverTimeResortAdmin['labels'] ?? []), @js($this->bookingCountOverTimeResortAdmin['datasets'][0]['data'] ?? []))"
                     x-init="initChart()">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>

        {{-- Revenue Distribution Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 md:p-6 mb-6" wire:key="resort-admin-revenue-chart-{{ $this->resortAdminFilter }}">
            <h3 class="text-base md:text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Revenue Distribution</h3>
            <div style="height: 400px; position: relative;" class="w-full"
                 x-data="pieChartData(@js($this->revenueDistributionResortAdmin['labels'] ?? []), @js($this->revenueDistributionResortAdmin['datasets'][0]['data'] ?? []), @js($this->revenueDistributionResortAdmin['datasets'][0]['backgroundColor'] ?? []), @js($this->revenueDistributionResortAdmin['datasets'][0]['borderColor'] ?? []))"
                 x-init="initChart()">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>
    @endif

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Chart initialization functions
            function chartData(labels, data, type, chartId) {
                return {
                    chart: null,
                    labels: labels,
                    data: data,
                    type: type,
                    chartId: chartId,
                    initChart() {
                        const self = this;
                        function initialize() {
                            if (typeof Chart === 'undefined') {
                                setTimeout(initialize, 100);
                                return;
                            }
                            if (self.chart) {
                                self.chart.destroy();
                            }
                            const ctx = self.$refs.canvas;
                            if (!ctx) {
                                setTimeout(initialize, 100);
                                return;
                            }
                            
                            const config = {
                                type: self.type,
                                data: {
                                    labels: self.labels,
                                    datasets: [{
                                        label: self.chartId === 'bookingsChart' ? 'Bookings' : 'Sales (₱)',
                                        data: self.data,
                                        backgroundColor: self.chartId === 'bookingsChart' ? 'rgba(59, 130, 246, 0.1)' : 'rgba(34, 197, 94, 0.1)',
                                        borderColor: self.chartId === 'bookingsChart' ? 'rgba(59, 130, 246, 1)' : 'rgba(34, 197, 94, 1)',
                                        borderWidth: 2,
                                        fill: true,
                                        tension: 0.4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top'
                                        },
                                        tooltip: (self.chartId === 'salesChart' || self.chartId === 'resortSalesChart') ? {
                                            callbacks: {
                                                label: function(context) {
                                                    return '₱ ' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                                }
                                            }
                                        } : {}
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: (self.chartId === 'salesChart' || self.chartId === 'resortSalesChart') ? {
                                                callback: function(value) {
                                                    return '₱ ' + value.toLocaleString('en-US');
                                                }
                                            } : {
                                                stepSize: 1
                                            }
                                        }
                                    }
                                }
                            };
                            
                            self.chart = new Chart(ctx, config);
                        }
                        initialize();
                    }
                };
            }

            function barChartData(labels, bookingsData, salesData) {
                return {
                    chart: null,
                    labels: labels,
                    bookingsData: bookingsData,
                    salesData: salesData,
                    initChart() {
                        const self = this;
                        function initialize() {
                            if (typeof Chart === 'undefined') {
                                setTimeout(initialize, 100);
                                return;
                            }
                            if (self.chart) {
                                self.chart.destroy();
                            }
                            const ctx = self.$refs.canvas;
                            if (!ctx) {
                                setTimeout(initialize, 100);
                                return;
                            }
                            
                            self.chart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: self.labels,
                                    datasets: [{
                                        label: 'Total Bookings',
                                        data: self.bookingsData,
                                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                        borderColor: 'rgba(59, 130, 246, 1)',
                                        borderWidth: 1,
                                        yAxisID: 'y'
                                    }, {
                                        label: 'Total Sales (₱)',
                                        data: self.salesData,
                                        backgroundColor: 'rgba(34, 197, 94, 0.5)',
                                        borderColor: 'rgba(34, 197, 94, 1)',
                                        borderWidth: 1,
                                        yAxisID: 'y1'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top'
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    if (context.datasetIndex === 1) {
                                                        return '₱ ' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                                    }
                                                    return context.parsed.y;
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            type: 'linear',
                                            display: true,
                                            position: 'left',
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            },
                                            title: {
                                                display: true,
                                                text: 'Bookings'
                                            }
                                        },
                                        y1: {
                                            type: 'linear',
                                            display: true,
                                            position: 'right',
                                            beginAtZero: true,
                                            ticks: {
                                                callback: function(value) {
                                                    return '₱ ' + value.toLocaleString('en-US');
                                                }
                                            },
                                            title: {
                                                display: true,
                                                text: 'Sales (₱)'
                                            },
                                            grid: {
                                                drawOnChartArea: false
                                            }
                                        }
                                    }
                                }
                            });
                        }
                        initialize();
                    }
                };
            }

            function barChartDataResortAdmin(labels, data) {
                return {
                    chart: null,
                    labels: labels,
                    data: data,
                    initChart() {
                        const self = this;
                        function initialize() {
                            if (typeof Chart === 'undefined') {
                                setTimeout(initialize, 100);
                                return;
                            }
                            if (self.chart) {
                                self.chart.destroy();
                            }
                            const ctx = self.$refs.canvas;
                            if (!ctx) {
                                setTimeout(initialize, 100);
                                return;
                            }
                            
                            self.chart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: self.labels,
                                    datasets: [{
                                        label: 'Bookings',
                                        data: self.data,
                                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                                        borderColor: 'rgba(59, 130, 246, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top'
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    }
                                }
                            });
                        }
                        initialize();
                    }
                };
            }

            function pieChartData(labels, data, backgroundColor, borderColor) {
                return {
                    chart: null,
                    labels: labels,
                    data: data,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    initChart() {
                        const self = this;
                        function initialize() {
                            if (typeof Chart === 'undefined') {
                                setTimeout(initialize, 100);
                                return;
                            }
                            if (self.chart) {
                                self.chart.destroy();
                            }
                            const ctx = self.$refs.canvas;
                            if (!ctx) {
                                setTimeout(initialize, 100);
                                return;
                            }
                            
                            self.chart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: self.labels,
                                    datasets: [{
                                        label: 'Revenue (₱)',
                                        data: self.data,
                                        backgroundColor: self.backgroundColor,
                                        borderColor: self.borderColor,
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'right'
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    const label = context.label || '';
                                                    const value = context.parsed || 0;
                                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                    const percentage = ((value / total) * 100).toFixed(1);
                                                    return label + ': ₱ ' + value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' (' + percentage + '%)';
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        }
                        initialize();
                    }
                };
            }
        </script>
    @endpush
</x-filament-panels::page>
<script>
    function startTime() {
        const today = new Date();
        const timeElement = document.getElementById('time');
        if (timeElement) {
            timeElement.innerHTML = 'Today is ' + today;
            setTimeout(startTime, 1000);
        }
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startTime);
    } else {
        startTime();
    }
</script>
