<div>
    <x-ui.card class="border-2 shadow-skeuo-card max-w-5xl mx-auto">
        <x-slot:header>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold leading-none tracking-tight">Metode Pembayaran</h3>
                    <p class="text-sm text-muted-foreground mt-1">Kelola metode pembayaran yang tersedia di sistem.</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="relative w-full sm:w-64">
                        <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <x-ui.input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari metode pembayaran..." class="pl-8" />
                    </div>
                    <x-ui.button wire:click="create">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Tambah Metode
                    </x-ui.button>
                </div>
            </div>
        </x-slot:header>
        
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b border-border bg-muted/50">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Metode Pembayaran</th>
                        <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Cash</th>
                        <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Debit</th>
                        <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">Credit</th>
                        <th class="h-12 px-4 text-center align-middle font-medium text-muted-foreground">E-Wallet</th>
                        <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse($paymentMethods as $pm)
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <td class="p-4 align-middle font-semibold">{{ $pm->name }}</td>
                        
                        <td class="p-4 align-middle text-center">
                            @if($pm->is_cash)
                                <span class="inline-flex items-center rounded-full bg-green-500/15 px-2 py-1 text-xs font-semibold text-green-700 dark:text-green-400">Ya</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-destructive/15 px-2 py-1 text-xs font-semibold text-destructive">Tidak</span>
                            @endif
                        </td>
                        
                        <td class="p-4 align-middle text-center">
                            @if($pm->is_debit)
                                <span class="inline-flex items-center rounded-full bg-green-500/15 px-2 py-1 text-xs font-semibold text-green-700 dark:text-green-400">Ya</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-destructive/15 px-2 py-1 text-xs font-semibold text-destructive">Tidak</span>
                            @endif
                        </td>
                        
                        <td class="p-4 align-middle text-center">
                            @if($pm->is_credit)
                                <span class="inline-flex items-center rounded-full bg-green-500/15 px-2 py-1 text-xs font-semibold text-green-700 dark:text-green-400">Ya</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-destructive/15 px-2 py-1 text-xs font-semibold text-destructive">Tidak</span>
                            @endif
                        </td>
                        
                        <td class="p-4 align-middle text-center">
                            @if($pm->is_wallet)
                                <span class="inline-flex items-center rounded-full bg-green-500/15 px-2 py-1 text-xs font-semibold text-green-700 dark:text-green-400">Ya</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-destructive/15 px-2 py-1 text-xs font-semibold text-destructive">Tidak</span>
                            @endif
                        </td>
                        
                        <td class="p-4 align-middle text-right">
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button wire:click="edit({{ $pm->id }})" variant="outline" size="sm" class="h-8 shadow-skeuo-sm">
                                    Edit
                                </x-ui.button>
                                <button wire:click="delete({{ $pm->id }})" wire:confirm="Yakin ingin menghapus metode pembayaran ini?" class="text-destructive hover:bg-destructive/10 p-1.5 rounded-md transition-colors">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-muted-foreground">
                            Tidak ada metode pembayaran ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <x-slot:footer>
            <div class="w-full pt-4 border-t border-border">
                {{ $paymentMethods->links() }}
            </div>
        </x-slot:footer>
    </x-ui.card>

    <!-- Modal Form -->
    <x-ui.modal wire:model="showModal" maxWidth="lg">
        <x-slot:title>
            {{ $isEdit ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran' }}
        </x-slot:title>

        <form wire:submit.prevent="save" id="pmForm" class="py-4 space-y-4">
            
            <!-- Nama Metode -->
            <div class="space-y-2">
                <label for="name" class="text-sm font-medium leading-none">
                    Nama Metode Pembayaran <span class="text-destructive">*</span>
                </label>
                <x-ui.input wire:model="name" id="name" placeholder="Misal: Cash, BCA, OVO, dll." />
                @error('name') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
            </div>

            <!-- Tipe Metode (Checkboxes) -->
            <div class="space-y-3 pt-2">
                <label class="text-sm font-medium leading-none">
                    Kategori Pembayaran
                </label>
                <div class="grid grid-cols-2 gap-3">
                    
                    <label class="flex items-center gap-2 p-3 rounded-lg border-2 border-border bg-card cursor-pointer hover:bg-muted/50 transition-colors">
                        <input type="checkbox" wire:model="is_cash" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20">
                        <span class="text-sm font-medium">Tunai (Cash)</span>
                    </label>

                    <label class="flex items-center gap-2 p-3 rounded-lg border-2 border-border bg-card cursor-pointer hover:bg-muted/50 transition-colors">
                        <input type="checkbox" wire:model="is_debit" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20">
                        <span class="text-sm font-medium">Kartu Debit</span>
                    </label>

                    <label class="flex items-center gap-2 p-3 rounded-lg border-2 border-border bg-card cursor-pointer hover:bg-muted/50 transition-colors">
                        <input type="checkbox" wire:model="is_credit" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20">
                        <span class="text-sm font-medium">Kartu Kredit</span>
                    </label>

                    <label class="flex items-center gap-2 p-3 rounded-lg border-2 border-border bg-card cursor-pointer hover:bg-muted/50 transition-colors">
                        <input type="checkbox" wire:model="is_wallet" class="w-4 h-4 rounded border-border text-primary focus:ring-primary/20">
                        <span class="text-sm font-medium">E-Wallet / QRIS</span>
                    </label>
                </div>
            </div>

        </form>

        <x-slot:footer>
            <x-ui.button variant="outline" wire:click="$set('showModal', false)">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" form="pmForm">
                Simpan
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
