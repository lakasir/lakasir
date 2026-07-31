<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Piutang (Receivables)</h2>
            <p class="text-muted-foreground">Kelola piutang pelanggan dari transaksi penjualan yang belum lunas.</p>
        </div>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari nama/no nota..." class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-8">
            </div>
            <select wire:model.live="statusFilter" class="flex h-9 w-full sm:w-48 items-center justify-between rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                <option value="">Semua Status</option>
                <option value="0">Belum Lunas (Unpaid)</option>
                <option value="1">Lunas (Paid off)</option>
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

    <div class="rounded-md border bg-card text-card-foreground shadow-sm">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Nota (Selling)</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Pelanggan</th>
                        <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Total Piutang</th>
                        <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Sisa Piutang</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Jatuh Tempo</th>
                        <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                        <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse($receivables as $receivable)
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle font-medium">
                                <span class="text-primary">#{{ $receivable->selling?->code ?? '-' }}</span>
                            </td>
                            <td class="p-4 align-middle">
                                {{ $receivable->member?->name ?? '-' }}
                            </td>
                            <td class="p-4 align-middle text-right">
                                Rp {{ number_format($receivable->total_receivable, 0, ',', '.') }}
                            </td>
                            <td class="p-4 align-middle text-right font-semibold text-destructive">
                                Rp {{ number_format($receivable->rest_receivable, 0, ',', '.') }}
                            </td>
                            <td class="p-4 align-middle">
                                {{ \Carbon\Carbon::parse($receivable->due_date)->format('d/m/Y') }}
                            </td>
                            <td class="p-4 align-middle text-center">
                                @if($receivable->status)
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 h-3 w-3"><path d="M20 6 9 17l-5-5"/></svg>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 h-3 w-3"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 align-middle text-right">
                                <a href="{{ route('receivables.show', $receivable->id) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Detail & Pembayaran">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-muted-foreground">
                                Tidak ada data piutang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $receivables->links() }}
    </div>
</div>
