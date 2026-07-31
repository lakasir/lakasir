<div>
    <x-ui.card class="border-2 shadow-skeuo-card">
        <x-slot:header>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold leading-none tracking-tight">Manajemen Produk</h3>
                    <p class="text-sm text-muted-foreground mt-1">Kelola data produk, harga, dan stok.</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="relative w-full sm:w-64">
                        <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <x-ui.input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari produk atau SKU..." class="pl-8" />
                    </div>
                    <x-ui.button wire:click="create">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Produk
                    </x-ui.button>
                </div>
            </div>
        </x-slot:header>
        
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b border-border bg-muted/50">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground w-[80px]">SKU</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nama Produk</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Harga Beli</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Harga Jual</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Stok</th>
                        <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse($products as $product)
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <td class="p-4 align-middle font-mono text-xs">{{ $product->sku ?? '-' }}</td>
                        <td class="p-4 align-middle font-medium">{{ $product->name }}</td>
                        <td class="p-4 align-middle">Rp {{ number_format($product->initial_price ?? 0, 0, ',', '.') }}</td>
                        <td class="p-4 align-middle">Rp {{ number_format($product->selling_price ?? 0, 0, ',', '.') }}</td>
                        <td class="p-4 align-middle">
                            @if($product->is_non_stock || $product->type === 'service')
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">
                                    Non-Stock
                                </span>
                            @else
                                {{ $product->stock }} {{ $product->unit }}
                            @endif
                        </td>
                        <td class="p-4 align-middle text-right">
                            <div class="flex items-center justify-end gap-2">
                                <x-ui.button wire:click="edit({{ $product->id }})" variant="outline" size="sm" class="h-8 shadow-skeuo-sm">
                                    Edit
                                </x-ui.button>
                                <button wire:click="delete({{ $product->id }})" wire:confirm="Yakin ingin menghapus produk ini?" class="text-destructive hover:bg-destructive/10 p-1.5 rounded-md transition-colors">
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
                            Tidak ada data produk ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <x-slot:footer>
            <div class="w-full pt-4 border-t border-border">
                {{ $products->links() }}
            </div>
        </x-slot:footer>
    </x-ui.card>

    <!-- Modal Form Produk -->
    <x-ui.modal wire:model="showModal" maxWidth="2xl">
        <x-slot:title>
            {{ $isEdit ? 'Edit Produk' : 'Tambah Produk Baru' }}
        </x-slot:title>

        <form wire:submit.prevent="save" id="productForm" class="py-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Nama Produk -->
                <div class="space-y-2 md:col-span-2">
                    <label for="name" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Nama Produk <span class="text-destructive">*</span>
                    </label>
                    <x-ui.input wire:model="name" id="name" placeholder="Masukkan nama produk" />
                    @error('name') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Kategori -->
                <div class="space-y-2 md:col-span-2">
                    <label for="category_id" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Kategori <span class="text-destructive">*</span>
                    </label>
                    <select wire:model="category_id" id="category_id" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <option value="">Pilih Kategori...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Tipe -->
                <div class="space-y-2">
                    <label for="type" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Tipe Produk <span class="text-destructive">*</span>
                    </label>
                    <select wire:model.live="type" id="type" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        <option value="product">Barang Fisik (Product)</option>
                        <option value="service">Jasa / Layanan (Service)</option>
                    </select>
                    @error('type') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>
                
                <!-- Is Non Stock (Only show if type is product) -->
                @if($type === 'product')
                <div class="space-y-2 flex items-center pt-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" wire:model.live="is_non_stock" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm font-medium">Bukan Barang Berstok (Non-Stock)</span>
                    </label>
                </div>
                @else
                <div class="hidden md:block"></div>
                @endif

                <!-- Harga Beli -->
                <div class="space-y-2">
                    <label for="initial_price" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Harga Beli / Modal Dasar <span class="text-destructive">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground text-sm">Rp</span>
                        <x-ui.input wire:model="initial_price" id="initial_price" type="number" min="0" class="pl-9" />
                    </div>
                    @error('initial_price') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Harga Jual -->
                <div class="space-y-2">
                    <label for="selling_price" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Harga Jual <span class="text-destructive">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-muted-foreground text-sm">Rp</span>
                        <x-ui.input wire:model="selling_price" id="selling_price" type="number" min="0" class="pl-9" />
                    </div>
                    @error('selling_price') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Stok -->
                <div class="space-y-2">
                    <label for="stock" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Stok <span class="text-destructive">*</span>
                    </label>
                    <x-ui.input wire:model="stock" id="stock" type="number" min="0" :disabled="$is_non_stock || $type === 'service'" />
                    @error('stock') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Unit -->
                <div class="space-y-2">
                    <label for="unit" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Satuan (Unit)
                    </label>
                    <x-ui.input wire:model="unit" id="unit" placeholder="Misal: PCS, BOX" />
                    @error('unit') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- SKU -->
                <div class="space-y-2">
                    <label for="sku" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        SKU
                    </label>
                    <x-ui.input wire:model="sku" id="sku" placeholder="Kosongkan untuk generate otomatis" />
                    @error('sku') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Barcode -->
                <div class="space-y-2">
                    <label for="barcode" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Barcode
                    </label>
                    <x-ui.input wire:model="barcode" id="barcode" placeholder="Scan barcode di sini" />
                    @error('barcode') <span class="text-xs text-destructive font-medium">{{ $message }}</span> @enderror
                </div>

            </div>
        </form>

        <x-slot:footer>
            <x-ui.button variant="outline" wire:click="$set('showModal', false)">
                Batal
            </x-ui.button>
            <x-ui.button type="submit" form="productForm">
                Simpan
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
