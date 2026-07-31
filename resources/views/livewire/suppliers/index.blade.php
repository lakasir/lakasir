<div>
    <x-ui.card class="border-2 shadow-skeuo-card">
        <x-slot:header>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold leading-none tracking-tight">Data Pemasok (Supplier)</h3>
                    <p class="text-sm text-muted-foreground mt-1">Kelola data vendor pemasok barang Anda.</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="relative w-full sm:w-64">
                        <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <x-ui.input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari perusahaan, PIC, email..." class="pl-8" />
                    </div>
                    <x-ui.button wire:click="create">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Tambah Supplier
                    </x-ui.button>
                </div>
            </div>
        </x-slot:header>
        
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b border-border bg-muted/50">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Perusahaan</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Kontak (PIC)</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Telepon</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Email</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Lokasi</th>
                        <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse($suppliers as $supplier)
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <td class="p-4 align-middle font-semibold">{{ $supplier->name }}</td>
                        <td class="p-4 align-middle">{{ $supplier->contact_name ?? '-' }}</td>
                        <td class="p-4 align-middle">{{ $supplier->phone_number ?? '-' }}</td>
                        <td class="p-4 align-middle text-muted-foreground">{{ $supplier->email ?? '-' }}</td>
                        <td class="p-4 align-middle">
                            @if($supplier->city)
                                {{ $supplier->city }} {{ $supplier->country ? ', ' . $supplier->country : '' }}
                            @else
                                <span class="text-muted-foreground">-</span>
                            @endif
                        </td>
                        <td class="p-4 align-middle text-right">
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button wire:click="edit({{ $supplier->id }})" variant="outline" size="sm" class="h-8 shadow-skeuo-sm">
                                    Edit
                                </x-ui.button>
                                <button wire:click="delete({{ $supplier->id }})" wire:confirm="Yakin ingin menghapus supplier ini?" class="text-destructive hover:bg-destructive/10 p-1.5 rounded-md transition-colors">
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
                            Tidak ada data pemasok ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <x-slot:footer>
            <div class="w-full pt-4 border-t border-border">
                {{ $suppliers->links() }}
            </div>
        </x-slot:footer>
    </x-ui.card>

    <!-- Modal Form Supplier -->
    <x-ui.modal wire:model="showModal" maxWidth="2xl">
        <x-slot:title>
            {{ $isEdit ? 'Edit Pemasok' : 'Tambah Pemasok Baru' }}
        </x-slot:title>

        <form wire:submit.prevent="save" id="supplierForm" class="py-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Nama Perusahaan -->
                <div class="space-y-2 md:col-span-2">
                    <label for="name" class="text-sm font-medium leading-none">
                        Nama Perusahaan <span class="text-destructive">*</span>
                    </label>
                    <x-ui.input wire:model="name" id="name" placeholder="PT / CV / Nama Usaha" />
                    @error('name') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Kontak PIC -->
                <div class="space-y-2">
                    <label for="contact_name" class="text-sm font-medium leading-none">
                        Nama PIC (Person in Charge)
                    </label>
                    <x-ui.input wire:model="contact_name" id="contact_name" placeholder="Nama orang yang bisa dihubungi" />
                    @error('contact_name') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Telepon -->
                <div class="space-y-2">
                    <label for="phone_number" class="text-sm font-medium leading-none">
                        No. Telepon / WhatsApp
                    </label>
                    <x-ui.input wire:model="phone_number" id="phone_number" placeholder="Misal: 081234567890" />
                    @error('phone_number') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2 md:col-span-2">
                    <label for="email" class="text-sm font-medium leading-none">
                        Alamat Email
                    </label>
                    <x-ui.input wire:model="email" id="email" type="email" placeholder="vendor@perusahaan.com" />
                    @error('email') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Alamat -->
                <div class="space-y-2 md:col-span-2">
                    <label for="address" class="text-sm font-medium leading-none">
                        Alamat Kantor / Gudang
                    </label>
                    <textarea wire:model="address" id="address" rows="3" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="Alamat lengkap..."></textarea>
                    @error('address') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Kota -->
                <div class="space-y-2">
                    <label for="city" class="text-sm font-medium leading-none">
                        Kota
                    </label>
                    <x-ui.input wire:model="city" id="city" placeholder="Misal: Jakarta" />
                    @error('city') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Kode Pos -->
                <div class="space-y-2">
                    <label for="postal_code" class="text-sm font-medium leading-none">
                        Kode Pos
                    </label>
                    <x-ui.input wire:model="postal_code" id="postal_code" placeholder="Misal: 12345" />
                    @error('postal_code') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Negara -->
                <div class="space-y-2 md:col-span-2">
                    <label for="country" class="text-sm font-medium leading-none">
                        Negara
                    </label>
                    <x-ui.input wire:model="country" id="country" placeholder="Misal: Indonesia" />
                    @error('country') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

            </div>
        </form>

        <x-slot:footer>
            <x-ui.button variant="outline" wire:click="$set('showModal', false)">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" form="supplierForm">
                Simpan
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
