<div class="space-y-6">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar Tabs -->
        <aside class="w-full md:w-64 shrink-0">
            <nav class="flex space-x-2 md:flex-col md:space-x-0 md:space-y-1">
                <button wire:click="$set('activeTab', 'selling')" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors {{ $activeTab === 'selling' ? 'bg-muted text-primary' : 'hover:bg-transparent hover:underline text-muted-foreground' }}">
                    Laporan Penjualan
                </button>
                <button wire:click="$set('activeTab', 'product')" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors {{ $activeTab === 'product' ? 'bg-muted text-primary' : 'hover:bg-transparent hover:underline text-muted-foreground' }}">
                    Laporan Stok Produk
                </button>
                <button wire:click="$set('activeTab', 'purchasing')" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors {{ $activeTab === 'purchasing' ? 'bg-muted text-primary' : 'hover:bg-transparent hover:underline text-muted-foreground' }}">
                    Laporan Pembelian
                </button>
                <button wire:click="$set('activeTab', 'cashier')" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors {{ $activeTab === 'cashier' ? 'bg-muted text-primary' : 'hover:bg-transparent hover:underline text-muted-foreground' }}">
                    Kinerja Kasir
                </button>
            </nav>
        </aside>

        <!-- Tab Content -->
        <div class="flex-1 max-w-5xl">
            <x-ui.card class="border-2 shadow-skeuo-card">
                <x-slot:header>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-semibold leading-none tracking-tight">
                                @if($activeTab === 'selling') Laporan Penjualan
                                @elseif($activeTab === 'product') Laporan Stok Produk
                                @elseif($activeTab === 'purchasing') Laporan Pembelian
                                @elseif($activeTab === 'cashier') Kinerja Kasir
                                @endif
                            </h3>
                            <p class="text-sm text-muted-foreground mt-1">Rangkuman data dan aktivitas.</p>
                        </div>
                        
                        <!-- Filter Tanggal (Tidak tampil di laporan stok) -->
                        @if($activeTab !== 'product')
                        <div class="flex items-center gap-2">
                            <x-ui.input type="date" wire:model.live="startDate" class="w-auto h-8 text-xs" />
                            <span class="text-xs text-muted-foreground">s/d</span>
                            <x-ui.input type="date" wire:model.live="endDate" class="w-auto h-8 text-xs" />
                        </div>
                        @endif
                    </div>
                </x-slot:header>
                
                <div class="overflow-x-auto">
                    <!-- Tab: Penjualan -->
                    @if($activeTab === 'selling')
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-muted-foreground bg-muted/50 uppercase border-y border-border">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">No. Invoice</th>
                                <th class="px-4 py-3">Kasir</th>
                                <th class="px-4 py-3 text-right">Total Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->reportData as $row)
                            <tr class="border-b border-border hover:bg-muted/30">
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 font-medium">{{ $row->invoice_number ?? 'INV-'.$row->id }}</td>
                                <td class="px-4 py-3">{{ $row->cashier?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($row->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">Tidak ada data penjualan pada rentang tanggal ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Tab: Produk -->
                    @elseif($activeTab === 'product')
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-muted-foreground bg-muted/50 uppercase border-y border-border">
                            <tr>
                                <th class="px-4 py-3">Kode/SKU</th>
                                <th class="px-4 py-3">Nama Produk</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3 text-right">Harga Jual</th>
                                <th class="px-4 py-3 text-right">Stok Aktif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->reportData as $row)
                            <tr class="border-b border-border hover:bg-muted/30">
                                <td class="px-4 py-3 text-muted-foreground">{{ $row->sku ?? '-' }}</td>
                                <td class="px-4 py-3 font-medium">{{ $row->name }}</td>
                                <td class="px-4 py-3">{{ $row->category?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($row->selling_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-bold">{{ $row->stock_count ?? 0 }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-muted-foreground">Belum ada data produk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Tab: Pembelian -->
                    @elseif($activeTab === 'purchasing')
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-muted-foreground bg-muted/50 uppercase border-y border-border">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">No. Referensi</th>
                                <th class="px-4 py-3">Supplier</th>
                                <th class="px-4 py-3 text-right">Total Pembelian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->reportData as $row)
                            <tr class="border-b border-border hover:bg-muted/30">
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>
                                <td class="px-4 py-3 font-medium">{{ $row->reference_number ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $row->supplier?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($row->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">Tidak ada data pembelian pada rentang tanggal ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Tab: Kasir -->
                    @elseif($activeTab === 'cashier')
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-muted-foreground bg-muted/50 uppercase border-y border-border">
                            <tr>
                                <th class="px-4 py-3">Nama Kasir</th>
                                <th class="px-4 py-3 text-center">Jumlah Transaksi</th>
                                <th class="px-4 py-3 text-right">Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->reportData as $row)
                            <tr class="border-b border-border hover:bg-muted/30">
                                <td class="px-4 py-3 font-medium">{{ $row->cashier?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">{{ $row->total_transactions }}</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($row->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-muted-foreground">Tidak ada aktivitas kasir pada rentang tanggal ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @endif
                </div>
                
                <div class="pt-4 flex gap-2 border-t border-border mt-2">
                    @if($activeTab === 'selling')
                        <a href="{{ route('selling-report.generate') }}?start={{ $startDate }}&end={{ $endDate }}" target="_blank" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 shadow-skeuo-btn">
                            Export Laporan Penjualan (PDF)
                        </a>
                    @elseif($activeTab === 'purchasing')
                        <a href="{{ route('purchasing-report.generate') }}?start={{ $startDate }}&end={{ $endDate }}" target="_blank" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 shadow-skeuo-btn">
                            Export Laporan Pembelian (PDF)
                        </a>
                    @elseif($activeTab === 'product')
                        <a href="{{ route('product-report.generate') }}" target="_blank" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 shadow-skeuo-btn">
                            Export Laporan Produk (PDF)
                        </a>
                    @elseif($activeTab === 'cashier')
                        <a href="{{ route('cashier-report.generate') }}?start={{ $startDate }}&end={{ $endDate }}" target="_blank" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 shadow-skeuo-btn">
                            Export Kinerja Kasir (PDF)
                        </a>
                    @endif
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
