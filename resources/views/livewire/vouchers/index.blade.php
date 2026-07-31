<div>
    <x-ui.card class="border-2 shadow-skeuo-card">
        <x-slot:header>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold leading-none tracking-tight">Manajemen Diskon & Voucher</h3>
                    <p class="text-sm text-muted-foreground mt-1">Kelola data voucher diskon, masa aktif, dan kuota.</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="relative w-full sm:w-64">
                        <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <x-ui.input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari nama atau kode..." class="pl-8" />
                    </div>
                    <x-ui.button wire:click="create">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Voucher
                    </x-ui.button>
                </div>
            </div>
        </x-slot:header>
        
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b border-border bg-muted/50">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Kode</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Voucher</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Potongan</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Masa Berlaku</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Kuota</th>
                        <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse($vouchers as $voucher)
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <td class="p-4 align-middle font-mono font-semibold">{{ $voucher->code }}</td>
                        <td class="p-4 align-middle font-medium">{{ $voucher->name }}</td>
                        <td class="p-4 align-middle">
                            @if($voucher->type === 'percentage')
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">
                                    {{ $voucher->nominal }}%
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">
                                    Rp {{ number_format($voucher->nominal, 0, ',', '.') }}
                                </span>
                            @endif
                        </td>
                        <td class="p-4 align-middle">
                            @php
                                $isExpired = now()->gt(\Carbon\Carbon::parse($voucher->expired));
                            @endphp
                            <div class="flex flex-col">
                                <span class="text-xs">{{ \Carbon\Carbon::parse($voucher->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($voucher->expired)->format('d M Y') }}</span>
                                @if($isExpired)
                                    <span class="text-xs text-destructive font-medium">Expired</span>
                                @endif
                            </div>
                        </td>
                        <td class="p-4 align-middle">
                            {{ number_format($voucher->kuota, 0, ',', '.') }}
                        </td>
                        <td class="p-4 align-middle text-right">
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button wire:click="edit({{ $voucher->id }})" variant="outline" size="sm" class="h-8 shadow-skeuo-sm">
                                    Edit
                                </x-ui.button>
                                <button wire:click="delete({{ $voucher->id }})" wire:confirm="Yakin ingin menghapus voucher ini?" class="text-destructive hover:bg-destructive/10 p-1.5 rounded-md transition-colors">
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
                            Tidak ada data diskon & voucher ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <x-slot:footer>
            <div class="w-full pt-4 border-t border-border">
                {{ $vouchers->links() }}
            </div>
        </x-slot:footer>
    </x-ui.card>

    <!-- Modal Form Voucher -->
    <x-ui.modal wire:model="showModal" maxWidth="2xl">
        <x-slot:title>
            {{ $isEdit ? 'Edit Voucher' : 'Tambah Voucher Baru' }}
        </x-slot:title>

        <form wire:submit.prevent="save" id="voucherForm" class="py-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Nama Voucher -->
                <div class="space-y-2 md:col-span-2">
                    <label for="name" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Nama Voucher <span class="text-destructive">*</span>
                    </label>
                    <x-ui.input wire:model="name" id="name" placeholder="Misal: Diskon Merdeka" />
                    @error('name') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Kode Voucher -->
                <div class="space-y-2">
                    <label for="code" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Kode Voucher <span class="text-destructive">*</span>
                    </label>
                    <x-ui.input wire:model="code" id="code" placeholder="Misal: MERDEKA45" class="uppercase" />
                    @error('code') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Kuota -->
                <div class="space-y-2">
                    <label for="kuota" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Kuota <span class="text-destructive">*</span>
                    </label>
                    <x-ui.input wire:model="kuota" id="kuota" type="number" min="0" placeholder="0" />
                    @error('kuota') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Tipe Diskon -->
                <div class="space-y-2">
                    <label for="type" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Tipe Diskon <span class="text-destructive">*</span>
                    </label>
                    <select wire:model.live="type" id="type" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <option value="percentage">Persentase (%)</option>
                        <option value="flat">Potongan Harga (Rp)</option>
                    </select>
                    @error('type') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>
                
                <!-- Nominal Diskon -->
                <div class="space-y-2">
                    <label for="nominal" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Nominal / Nilai Diskon <span class="text-destructive">*</span>
                    </label>
                    <div class="relative">
                        @if($type === 'flat')
                            <span class="absolute left-3 top-2.5 text-muted-foreground text-sm">Rp</span>
                            <x-ui.input wire:model="nominal" id="nominal" type="number" min="0" class="pl-9" />
                        @else
                            <x-ui.input wire:model="nominal" id="nominal" type="number" min="0" max="100" class="pr-9" />
                            <span class="absolute right-3 top-2.5 text-muted-foreground text-sm">%</span>
                        @endif
                    </div>
                    @error('nominal') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Minimal Belanja -->
                <div class="space-y-2 md:col-span-2">
                    <label for="minimal_buying" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Minimal Belanja <span class="text-destructive">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground text-sm">Rp</span>
                        <x-ui.input wire:model="minimal_buying" id="minimal_buying" type="number" min="0" class="pl-9" placeholder="0 untuk tanpa minimal belanja" />
                    </div>
                    @error('minimal_buying') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Tanggal Mulai -->
                <div class="space-y-2">
                    <label for="start_date" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Tanggal Mulai <span class="text-destructive">*</span>
                    </label>
                    <x-ui.input wire:model="start_date" id="start_date" type="date" />
                    @error('start_date') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Tanggal Berakhir -->
                <div class="space-y-2">
                    <label for="expired" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Tanggal Berakhir <span class="text-destructive">*</span>
                    </label>
                    <x-ui.input wire:model="expired" id="expired" type="date" />
                    @error('expired') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

            </div>
        </form>

        <x-slot:footer>
            <x-ui.button variant="outline" wire:click="$set('showModal', false)">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" form="voucherForm">
                Simpan
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
