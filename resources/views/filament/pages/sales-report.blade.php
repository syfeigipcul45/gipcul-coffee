<x-filament::page>
    <div class="space-y-6">
        {{-- Filter Form --}}
        <div class="filament-forms-card-component p-6 bg-white rounded-xl shadow">
            {{ $this->form }}
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Sales Chart --}}
            <div class="filament-forms-card-component p-6 bg-white rounded-xl shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Grafik Penjualan Harian (Rp) -
                    {{ \Carbon\Carbon::create($this->getCurrentYear(), $this->getCurrentMonth(), 1)->translatedFormat('F Y') }}
                </h3>
                <div class="chart-container" style="position: relative; height:400px; width:100%">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            {{-- Produk Chart --}}
            <div class="filament-forms-card-component p-6 bg-white rounded-xl shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Grafik Produk Harian -
                    {{ \Carbon\Carbon::create($this->getCurrentYear(), $this->getCurrentMonth(), 1)->translatedFormat('F Y') }}
                </h3>
                <div class="chart-container" style="position: relative; height:400px; width:100%">
                    <canvas id="productChart"></canvas>
                </div>
            </div>

            {{-- Top Products Chart --}}
            <div class="filament-forms-card-component p-6 bg-white rounded-xl shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    5 Produk Terlaris -
                    {{ \Carbon\Carbon::create($this->getCurrentYear(), $this->getCurrentMonth(), 1)->translatedFormat('F Y') }}
                </h3>
                <div class="chart-container" style="position: relative; height:400px; width:100%">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Chart instances
            let salesChart, productChart, topProductsChart;
            var internalData = [300, 50, 100, 75];

            var graphColors = [];
            var graphOutlines = [];
            var hoverColor = [];

            var internalDataLength = internalData.length;
            i = 0;
            while (i <= internalDataLength) {
                var randomR = Math.floor((Math.random() * 130) + 100);
                var randomG = Math.floor((Math.random() * 130) + 100);
                var randomB = Math.floor((Math.random() * 130) + 100);

                var graphBackground = "rgb(" +
                    randomR + ", " +
                    randomG + ", " +
                    randomB + ")";
                graphColors.push(graphBackground);

                var graphOutline = "rgb(" +
                    (randomR - 80) + ", " +
                    (randomG - 80) + ", " +
                    (randomB - 80) + ")";
                graphOutlines.push(graphOutline);

                var hoverColors = "rgb(" +
                    (randomR + 25) + ", " +
                    (randomG + 25) + ", " +
                    (randomB + 25) + ")";
                hoverColor.push(hoverColors);

                i++;
            };

            function parseChartData(data) {
                try {
                    if (typeof data === 'string') {
                        return JSON.parse(data);
                    }
                    return data;
                } catch (e) {
                    console.error('Error parsing chart data:', e);
                    return {
                        labels: ['Error loading data'],
                        data: [0]
                    };
                }
            }

            // Initialize charts
            function initCharts() {
                const salesData = parseChartData(@json($this->getSalesData()));
                const topProductsData = parseChartData(@json($this->getTopProductsData()));
                const productData = parseChartData(@json($this->getSumQty()));

                renderSalesChart(salesData);
                renderTopProductsChart(topProductsData);
                renderProductChart(productData);
            }

            // Render sales chart
            function renderSalesChart(data) {
                const ctx = document.getElementById('salesChart')?.getContext('2d');
                if (!ctx) return;

                // Validate data structure
                if (!Array.isArray(data?.labels) || !Array.isArray(data?.data)) {
                    console.error('Invalid sales data format:', data);
                    return;
                }

                if (salesChart) {
                    salesChart.destroy();
                }

                salesChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Total Penjualan (Rp)',
                            data: data.data,
                            backgroundColor: graphColors,
                            borderColor: graphOutlines,
                            hoverBackgroundColor: hoverColor,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.raw.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Product sales chart
            function renderProductChart(data) {
                const ctx = document.getElementById('productChart')?.getContext('2d');
                if (!ctx) return;

                // Validate data structure
                if (!Array.isArray(data?.labels) || !Array.isArray(data?.data)) {
                    console.error('Invalid sales data format:', data);
                    return;
                }

                if (productChart) {
                    productChart.destroy();
                }

                productChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Total Product',
                            data: data.data,
                            backgroundColor: graphColors,
                            borderColor: graphOutlines,
                            hoverBackgroundColor: hoverColor,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return + context.raw.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Render top products chart
            function renderTopProductsChart(data) {
                const ctx = document.getElementById('topProductsChart')?.getContext('2d');
                if (!ctx) return;

                // Validate data structure
                if (!Array.isArray(data?.labels) || !Array.isArray(data?.data)) {
                    console.error('Invalid products data format:', data);
                    return;
                }

                if (topProductsChart) {
                    topProductsChart.destroy();
                }

                topProductsChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Jumlah Terjual',
                            data: data.data,
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.7)',
                                'rgba(59, 130, 246, 0.7)',
                                'rgba(16, 185, 129, 0.7)',
                                'rgba(245, 158, 11, 0.7)',
                                'rgba(139, 92, 246, 0.7)'
                            ],
                            borderColor: [
                                'rgba(239, 68, 68, 1)',
                                'rgba(59, 130, 246, 1)',
                                'rgba(16, 185, 129, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(139, 92, 246, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${context.raw || '0'} unit`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Livewire hooks for auto-updating charts
            document.addEventListener('DOMContentLoaded', function() {
                initCharts();

                // Refresh when Livewire updates
                Livewire.hook('message.processed', (message, component) => {
                    if (component.fingerprint.name === 'filament.pages.sales-report') {
                        initCharts();
                    }
                });
            });
        </script>
    @endpush
</x-filament::page>
