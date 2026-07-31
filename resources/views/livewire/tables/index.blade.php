<div>
    <x-ui.card class="border-2 shadow-skeuo-card max-w-4xl mx-auto">
        <x-slot:header>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold leading-none tracking-tight">Manajemen Meja</h3>
                    <p class="text-sm text-muted-foreground mt-1">Kelola daftar meja untuk pesanan Dine-in.</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="relative w-full sm:w-64">
                        <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <x-ui.input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari nomor meja..." class="pl-8" />
                    </div>
                    <x-ui.button wire:click="create">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Tambah Meja
                    </x-ui.button>
                </div>
            </div>
        </x-slot:header>
        
        <div class="p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @forelse($tables as $table)
                <div class="group relative flex flex-col items-center justify-center p-6 border-2 border-border rounded-xl bg-card hover:border-primary/50 hover:shadow-skeuo-sm transition-all text-center">
                    <div class="w-12 h-12 bg-muted/50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-xl">{{ $table->number }}</h4>
                    
                    <!-- Action Overlay -->
                    <div class="absolute inset-0 bg-background/90 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 rounded-xl border border-primary/20">
                        <button wire:click="edit({{ $table->id }})" class="p-2 bg-primary text-primary-foreground rounded-md shadow-sm hover:brightness-110 transition-all">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        </button>
                        <button wire:click="delete({{ $table->id }})" wire:confirm="Yakin ingin menghapus meja ini?" class="p-2 bg-destructive text-destructive-foreground rounded-md shadow-sm hover:brightness-110 transition-all">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-muted-foreground border-2 border-dashed border-border rounded-xl">
                    Tidak ada meja ditemukan.
                </div>
            @endforelse
        </div>
        
        @if($tables->hasPages())
        <x-slot:footer>
            <div class="w-full pt-4 border-t border-border">
                {{ $tables->links() }}
            </div>
        </x-slot:footer>
        @endif
    </x-ui.card>

    <!-- Modal Form -->
    <x-ui.modal wire:model="showModal" maxWidth="sm">
        <x-slot:title>
            {{ $isEdit ? 'Edit Meja' : 'Tambah Meja' }}
        </x-slot:title>

        <form wire:submit.prevent="save" id="tableForm" class="py-4">
            <div class="space-y-2">
                <label for="number" class="text-sm font-medium leading-none">
                    Nomor / Nama Meja <span class="text-destructive">*</span>
                </label>
                <x-ui.input wire:model="number" id="number" placeholder="Misal: 01, VIP-01, dll." autofocus />
                @error('number') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
            </div>
        </form>

        <x-slot:footer>
            <x-ui.button variant="outline" wire:click="$set('showModal', false)">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" form="tableForm">
                Simpan
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
