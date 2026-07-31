<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('sellings.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-accent hover:text-accent-foreground h-9 w-9">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m15 18-6-6 6-6"/></svg>
                <span class="sr-only">Kembali</span>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Detail Transaksi</h2>
                <p class="text-muted-foreground">{{ $selling->number }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('selling.print', $selling->id) }}" target="_blank" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                Cetak Struk
            </a>
            @if(!$selling->is_paid)
                <button wire:click="markAsPaid" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                    Tandai Lunas
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-3">
        <!-- Main Content: Details -->
        <div class="md:col-span-2 space-y-6">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="flex flex-col space-y-1.5 p-6 border-b">
                    <h3 class="font-semibold leading-none tracking-tight">Item Pembelian</h3>
                </div>
                <div class="p-0">
                    <table class="w-full text-sm">
                        <thead class="[&_tr]:border-b bg-muted/50">
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Produk</th>
                                <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Qty</th>
                                <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Harga</th>
                                <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @foreach($selling->sellingDetails as $detail)
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="p-4 align-middle">
                                        <div class="font-medium">{{ $detail->product?->name ?? 'Produk Dihapus' }}</div>
                                        @if($detail->discount_price > 0)
                                            <div class="text-xs text-green-600 dark:text-green-400 mt-1">Diskon: Rp {{ number_format($detail->discount_price, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle text-center">
                                        {{ $detail->qty }}
                                    </td>
                                    <td class="p-4 align-middle text-right text-muted-foreground">
                                        Rp {{ number_format($detail->price_per_unit, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4 align-middle text-right font-medium">
                                        Rp {{ number_format($detail->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Catatan jika ada -->
            @if($selling->note)
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="flex flex-col space-y-1.5 p-6 border-b">
                    <h3 class="font-semibold leading-none tracking-tight">Catatan Transaksi</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-muted-foreground">{{ $selling->note }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="flex flex-col space-y-1.5 p-6 border-b">
                    <h3 class="font-semibold leading-none tracking-tight">Ringkasan</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-muted-foreground">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($selling->total_price, 0, ',', '.') }}</span>
                    </div>
                    @if($selling->discount_price > 0 || $selling->total_discount_per_item > 0)
                    <div class="flex justify-between items-center text-sm text-green-600 dark:text-green-400">
                        <span>Diskon</span>
                        <span>- Rp {{ number_format($selling->discount_price + $selling->total_discount_per_item, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($selling->tax_price > 0)
                    <div class="flex justify-between items-center text-sm text-amber-600 dark:text-amber-400">
                        <span>Pajak/Biaya Tambahan</span>
                        <span>+ Rp {{ number_format($selling->tax_price, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="h-px bg-border my-2"></div>
                    
                    <div class="flex justify-between items-center font-bold text-lg">
                        <span>Total</span>
                        <span>Rp {{ number_format($selling->grand_total_price, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="h-px bg-border my-2"></div>

                    <div class="flex justify-between items-center text-sm">
                        <span class="text-muted-foreground">Metode Pembayaran</span>
                        <span class="font-medium">{{ $selling->paymentMethod?->name ?? 'Tunai' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-muted-foreground">Bayar</span>
                        <span class="font-medium">Rp {{ number_format($selling->pay, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-muted-foreground">Kembali</span>
                        <span class="font-medium">Rp {{ number_format(max(0, $selling->pay - $selling->grand_total_price), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="flex flex-col space-y-1.5 p-6 border-b">
                    <h3 class="font-semibold leading-none tracking-tight">Informasi Transaksi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <div class="text-xs text-muted-foreground mb-1">Status Pembayaran</div>
                        @if($selling->is_paid)
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Lunas</span>
                        @else
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-destructive/20 text-destructive">Belum Lunas</span>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs text-muted-foreground mb-1">Waktu Transaksi</div>
                        <div class="text-sm font-medium">{{ \Carbon\Carbon::parse($selling->created_at)->format('d F Y, H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted-foreground mb-1">Pelanggan</div>
                        <div class="text-sm font-medium">{{ $selling->member?->name ?? 'Umum' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted-foreground mb-1">Kasir</div>
                        <div class="text-sm font-medium">{{ $selling->user?->name ?? 'Sistem' }}</div>
                    </div>
                    @if($selling->table)
                    <div>
                        <div class="text-xs text-muted-foreground mb-1">Meja / Area</div>
                        <div class="text-sm font-medium">{{ $selling->table->name }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
