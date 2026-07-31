<div class="space-y-6">
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        
        <!-- Widget: Total Penjualan -->
        <x-ui.card class="border-2 border-primary/20 bg-card shadow-skeuo">
            <x-slot:header>
                <div class="flex flex-row items-center justify-between pb-2">
                    <h3 class="text-sm font-medium tracking-tight">Pendapatan Hari Ini</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="h-4 w-4 text-muted-foreground">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
            </x-slot:header>
            <div class="text-2xl font-bold">Rp {{ number_format($totalRevenue['total'], 0, ',', '.') }}</div>
            <p class="text-xs text-muted-foreground mt-1 flex flex-row items-center gap-1">
                @if($totalRevenue['trend'] === 'increase')
                    <span class="text-green-500 font-medium">{{ $totalRevenue['percentage'] }}%</span> 
                @elseif($totalRevenue['trend'] === 'decrease')
                    <span class="text-red-500 font-medium">{{ $totalRevenue['percentage'] }}%</span>
                @else
                    <span class="text-yellow-500 font-medium">{{ $totalRevenue['percentage'] }}%</span>
                @endif
                dari hari kemarin
            </p>
        </x-ui.card>

        <!-- Widget: Transaksi -->
        <x-ui.card class="border-2 border-primary/20 bg-card shadow-skeuo">
            <x-slot:header>
                <div class="flex flex-row items-center justify-between pb-2">
                    <h3 class="text-sm font-medium tracking-tight">Total Transaksi</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="h-4 w-4 text-muted-foreground">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </x-slot:header>
            <div class="text-2xl font-bold">{{ $salesToday }}</div>
            <p class="text-xs text-muted-foreground mt-1">
                Total struk tercetak hari ini
            </p>
        </x-ui.card>

        <!-- Widget: Stok Menipis (Static for now) -->
        <x-ui.card class="border-2 border-destructive/30 bg-card shadow-skeuo">
            <x-slot:header>
                <div class="flex flex-row items-center justify-between pb-2">
                    <h3 class="text-sm font-medium tracking-tight text-destructive">Diskon Diberikan</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="h-4 w-4 text-destructive">
                        <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"></path>
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                    </svg>
                </div>
            </x-slot:header>
            <div class="text-2xl font-bold text-destructive">Rp {{ number_format($discountToday, 0, ',', '.') }}</div>
            <p class="text-xs text-muted-foreground mt-1">
                Akumulasi diskon hari ini
            </p>
        </x-ui.card>

        <!-- Widget: Active Users -->
        <x-ui.card class="border-2 border-primary/20 bg-card shadow-skeuo">
            <x-slot:header>
                <div class="flex flex-row items-center justify-between pb-2">
                    <h3 class="text-sm font-medium tracking-tight">Kasir Aktif</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="h-4 w-4 text-muted-foreground">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                    </svg>
                </div>
            </x-slot:header>
            <div class="text-2xl font-bold">+1</div>
            <p class="text-xs text-muted-foreground mt-1">
                Aktif saat ini
            </p>
        </x-ui.card>

    </div>
    
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-7">
        <!-- Chart Section -->
        <x-ui.card class="col-span-4 border-2 shadow-skeuo-card">
            <x-slot:header>
                <h3 class="font-semibold leading-none tracking-tight">Grafik Penjualan (7 Hari Terakhir)</h3>
            </x-slot:header>
            <div 
                x-data="salesChart(@js($salesChartData))" 
                class="h-[300px] w-full"
            >
                <div x-ref="chart" class="w-full h-full"></div>
            </div>
        </x-ui.card>

        <!-- Recent Sales Section -->
        <x-ui.card class="col-span-3 border-2 shadow-skeuo-card">
            <x-slot:header>
                <h3 class="font-semibold leading-none tracking-tight">Transaksi Terakhir</h3>
                <p class="text-sm text-muted-foreground">Transaksi terakhir di sistem POS.</p>
            </x-slot:header>
            <div class="space-y-6">
                
                @forelse($recentTransactions as $transaction)
                <div class="flex items-center">
                    <div class="space-y-1">
                        <p class="text-sm font-medium leading-none">{{ $transaction->invoice_number ?? 'INV-'.$transaction->id }}</p>
                        <p class="text-xs text-muted-foreground">Kasir: {{ $transaction->cashier?->name ?? 'Sistem' }}</p>
                    </div>
                    <div class="ml-auto font-medium">
                        +Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                    </div>
                </div>
                @empty
                <div class="text-center text-sm text-muted-foreground py-4">
                    Belum ada transaksi
                </div>
                @endforelse
                
            </div>
        </x-ui.card>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('salesChart', (data) => ({
            chart: null,
            init() {
                let options = {
                    series: [{
                        name: 'Pendapatan',
                        data: data.series
                    }],
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                        parentHeightOffset: 0,
                    },
                    colors: ['#f97316'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.05,
                            stops: [0, 90, 100]
                        }
                    },
                    dataLabels: { enabled: false },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    xaxis: {
                        categories: data.categories,
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: {
                            style: { colors: '#64748b', fontSize: '12px' }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                            },
                            style: { colors: '#64748b', fontSize: '12px' }
                        }
                    },
                    grid: {
                        borderColor: '#e2e8f0',
                        strokeDashArray: 4,
                        yaxis: { lines: { show: true } }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                            }
                        }
                    }
                };
                
                this.chart = new ApexCharts(this.$refs.chart, options);
                this.chart.render();
            }
        }));
    });
    </script>
</div>
