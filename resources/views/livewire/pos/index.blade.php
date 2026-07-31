<div class="flex flex-col lg:flex-row gap-6 lg:h-[calc(100vh-8rem)]">
    <!-- Kiri: Daftar Produk -->
    <div class="flex-1 flex flex-col gap-4 overflow-hidden min-h-[500px] lg:min-h-0">
        <x-ui.card class="p-4 shrink-0 shadow-skeuo-sm">
            <div class="relative w-full">
                <svg class="absolute left-3 top-3 h-5 w-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <x-ui.input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari produk (Nama atau SKU)..." class="pl-10 h-12 text-lg bg-muted/50" />
            </div>
        </x-ui.card>

        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 pb-4">
                @forelse($products as $product)
                <div wire:click="addToCart({{ $product->id }})" class="bg-card border-2 border-border rounded-xl p-3 flex flex-col gap-2 cursor-pointer hover:border-primary/50 transition-colors shadow-skeuo-sm relative overflow-hidden group">
                    <div class="aspect-square bg-muted/30 rounded-lg flex items-center justify-center text-muted-foreground">
                        <svg class="w-8 h-8 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex flex-col flex-1 justify-between">
                        <div>
                            <p class="text-xs font-mono text-muted-foreground mb-1">{{ $product->sku ?? '-' }}</p>
                            <h4 class="font-semibold text-sm leading-tight line-clamp-2">{{ $product->name }}</h4>
                        </div>
                        <p class="text-primary font-bold mt-2">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</p>
                    </div>
                    
                    <!-- Overlay Hover -->
                    <div class="absolute inset-0 bg-primary/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <x-ui.button size="sm" class="shadow-skeuo">Tambah</x-ui.button>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-muted-foreground bg-card border-2 border-dashed rounded-xl">
                    <svg class="mx-auto h-12 w-12 text-muted-foreground/50 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <p>Produk tidak ditemukan.</p>
                </div>
                @endforelse
            </div>
            
            <div class="py-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <!-- Kanan: Keranjang Belanja -->
    <x-ui.card class="w-full lg:w-[400px] shrink-0 flex flex-col h-full border-2 shadow-skeuo-card relative">
        <!-- Loading Overlay -->
        <div wire:loading wire:target="addToCart, removeFromCart, updateQty" class="absolute inset-0 bg-background/50 backdrop-blur-sm z-10 flex items-center justify-center rounded-xl">
            <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div class="p-4 border-b border-border bg-muted/30">
            <h3 class="font-bold text-lg">Keranjang Belanja</h3>
            <p class="text-sm text-muted-foreground">{{ count($cart) }} Item terpilih</p>
        </div>
        
        <div class="flex-1 overflow-y-auto p-4 flex flex-col custom-scrollbar">
            @if(count($cart) === 0)
            <div class="flex flex-col items-center justify-center text-muted-foreground h-full">
                <svg class="w-16 h-16 opacity-20 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-sm">Keranjang masih kosong.</p>
                <p class="text-xs mt-1">Pilih produk di sebelah kiri.</p>
            </div>
            @else
            <div class="space-y-3">
                @foreach($cart as $item)
                <div class="flex gap-3 items-center border border-border p-2 rounded-lg bg-background shadow-sm">
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-sm truncate">{{ $item['name'] }}</h4>
                        <p class="text-xs text-muted-foreground">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="flex items-center gap-1 bg-muted/50 rounded-md p-1 border border-border">
                        <button wire:click="updateQty('{{ $item['id'] }}', 'decrement')" class="w-6 h-6 flex items-center justify-center rounded hover:bg-background shadow-sm transition-colors text-muted-foreground hover:text-foreground">
                            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                        </button>
                        <span class="text-xs font-medium w-6 text-center">{{ $item['qty'] }}</span>
                        <button wire:click="updateQty('{{ $item['id'] }}', 'increment')" class="w-6 h-6 flex items-center justify-center rounded hover:bg-background shadow-sm transition-colors text-muted-foreground hover:text-foreground">
                            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </button>
                    </div>
                    
                    <div class="text-right ml-2 min-w-[70px]">
                        <p class="font-bold text-sm text-primary">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                    </div>
                    
                    <button wire:click="removeFromCart('{{ $item['id'] }}')" class="p-1.5 text-destructive hover:bg-destructive/10 rounded-md transition-colors ml-1">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <div class="p-4 border-t border-border bg-muted/10 space-y-4">
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Diskon</span>
                    <span class="font-medium text-destructive">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Pajak (0%)</span>
                    <span class="font-medium">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold pt-2 border-t border-border">
                    <span>Total</span>
                    <span class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <x-ui.button class="w-full h-12 text-lg shadow-skeuo" :disabled="count($cart) === 0">
                Proses Pembayaran
            </x-ui.button>
        </div>
    </x-ui.card>

    <style>
    /* Custom Scrollbar for inner areas */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: hsl(var(--border)); 
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: hsl(var(--muted-foreground) / 0.5); 
    }
    </style>
</div>
