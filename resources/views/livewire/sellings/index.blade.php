<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Riwayat Transaksi</h2>
            <p class="text-muted-foreground">Kelola riwayat penjualan kasir dan detail struk transaksi.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari nomor/pelanggan/kasir..." class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-8">
            </div>
            <select wire:model.live="paymentStatus" class="flex h-9 w-full sm:w-48 items-center justify-between rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                <option value="">Semua Status</option>
                <option value="1">Lunas</option>
                <option value="0">Belum Lunas</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <select wire:model.live="perPage" class="flex h-9 items-center justify-between rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>
    </div>

    <div class="rounded-md border bg-card">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Waktu</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">No. Transaksi</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Pelanggan</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Kasir</th>
                        <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Total</th>
                        <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Status Pembayaran</th>
                        <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse($sellings as $selling)
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle">
                                {{ \Carbon\Carbon::parse($selling->created_at)->format('d M Y, H:i') }}
                            </td>
                            <td class="p-4 align-middle font-medium">
                                {{ $selling->number }}
                            </td>
                            <td class="p-4 align-middle">
                                {{ $selling->member?->name ?? 'Umum' }}
                            </td>
                            <td class="p-4 align-middle">
                                {{ $selling->user?->name ?? 'Sistem' }}
                            </td>
                            <td class="p-4 align-middle text-right font-medium">
                                Rp {{ number_format($selling->grand_total_price, 0, ',', '.') }}
                            </td>
                            <td class="p-4 align-middle text-center">
                                @if($selling->is_paid)
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground">
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 align-middle text-right">
                                <a href="{{ route('sellings.show', $selling->id) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-accent hover:text-accent-foreground h-8 px-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-muted-foreground">
                                Tidak ada data transaksi ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $sellings->links() }}
    </div>
</div>
