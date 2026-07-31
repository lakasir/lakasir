<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('purchasings.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-accent hover:text-accent-foreground h-9 w-9 border border-input shadow-sm bg-background">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Order {{ $purchasing->number }}</h2>
                <p class="text-muted-foreground">Detail pesanan pembelian dan item barang (stok).</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($purchasing->status != 'approved')
                @if($purchasing->status != 'pending')
                <button wire:click="updatePurchasingStatus('pending')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                    Set Pending
                </button>
                @endif
                @if($purchasing->status != 'reviewing')
                <button wire:click="updatePurchasingStatus('reviewing')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                    Set Reviewing
                </button>
                @endif
                @if(can('approve purchasing'))
                <button wire:click="updatePurchasingStatus('approved')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-green-600 text-white shadow hover:bg-green-600/90 h-9 px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><path d="M20 6 9 17l-5-5"/></svg>
                    Approve
                </button>
                @endif
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-xl border bg-card text-card-foreground shadow">
            <div class="p-6">
                <h3 class="font-semibold leading-none tracking-tight mb-4 text-lg">Informasi Order</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Supplier</dt>
                        <dd class="text-sm font-semibold mt-1">{{ $purchasing->supplier?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">No. Telepon</dt>
                        <dd class="text-sm font-semibold mt-1">{{ $purchasing->supplier?->phone_number ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Tgl Order</dt>
                        <dd class="text-sm font-semibold mt-1">{{ \Carbon\Carbon::parse($purchasing->date)->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Jatuh Tempo</dt>
                        <dd class="text-sm font-semibold mt-1">{{ \Carbon\Carbon::parse($purchasing->due_date)->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Metode Pembayaran</dt>
                        <dd class="text-sm font-semibold mt-1">{{ $purchasing->paymentMethod?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Status Lunas</dt>
                        <dd class="mt-1">
                            @if($purchasing->payment_status)
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Lunas</span>
                            @else
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-secondary text-secondary-foreground">Belum</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Status Order</dt>
                        <dd class="mt-1">
                            @php
                                $statusColor = match($purchasing->status) {
                                    'pending' => 'bg-secondary text-secondary-foreground',
                                    'reviewing' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                    'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    default => 'bg-secondary text-secondary-foreground'
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $statusColor }}">
                                {{ Str::title($purchasing->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <div class="rounded-xl border bg-card text-card-foreground shadow">
            <div class="p-6">
                <h3 class="font-semibold leading-none tracking-tight mb-4 text-lg">Ringkasan Harga & Nota</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Total Harga Beli</dt>
                        <dd class="text-lg font-bold mt-1 text-primary">Rp {{ number_format($purchasing->total_initial_price, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Total Harga Jual (Estimasi)</dt>
                        <dd class="text-lg font-bold mt-1 text-green-600 dark:text-green-400">Rp {{ number_format($purchasing->total_selling_price, 0, ',', '.') }}</dd>
                    </div>
                </dl>
                
                @if($purchasing->image)
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground mb-2">Nota / Bukti (Image)</dt>
                        <a href="{{ Storage::url($purchasing->image) }}" target="_blank" class="block overflow-hidden rounded-md border max-w-xs">
                            <img src="{{ Storage::url($purchasing->image) }}" alt="Nota Pembelian" class="object-cover h-32 w-full transition-transform hover:scale-105">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Items Section -->
    <div class="rounded-xl border bg-card text-card-foreground shadow mt-6">
        <div class="flex flex-col space-y-1.5 p-6 border-b sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h3 class="font-semibold leading-none tracking-tight text-lg">Item Pembelian</h3>
                <p class="text-sm text-muted-foreground mt-1">Daftar produk (stok) yang termasuk dalam order ini.</p>
            </div>
            @if($purchasing->status != 'approved')
            <button x-data @click="$dispatch('open-modal', 'add-item-modal')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Tambah Item
            </button>
            @endif
        </div>
        <div class="p-0">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom text-sm">
                    <thead class="[&_tr]:border-b bg-muted/50">
                        <tr class="border-b transition-colors">
                            <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Produk</th>
                            <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Qty (Stok)</th>
                            <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Expired</th>
                            <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Harga Beli Satuan</th>
                            <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Harga Jual Satuan</th>
                            <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Subtotal Beli</th>
                            @if($purchasing->status != 'approved')
                            <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground w-[80px]">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                        @forelse($purchasing->stocks as $stock)
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="p-4 align-middle font-medium">
                                    {{ $stock->product->name }}
                                </td>
                                <td class="p-4 align-middle text-center">
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-secondary">
                                        {{ $stock->init_stock }}
                                    </span>
                                </td>
                                <td class="p-4 align-middle">
                                    {{ $stock->expired ? \Carbon\Carbon::parse($stock->expired)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="p-4 align-middle text-right">
                                    Rp {{ number_format($stock->initial_price, 0, ',', '.') }}
                                </td>
                                <td class="p-4 align-middle text-right text-muted-foreground">
                                    Rp {{ number_format($stock->selling_price, 0, ',', '.') }}
                                </td>
                                <td class="p-4 align-middle text-right font-medium">
                                    Rp {{ number_format($stock->total_initial_price, 0, ',', '.') }}
                                </td>
                                @if($purchasing->status != 'approved')
                                <td class="p-4 align-middle text-center">
                                    <button wire:click="removeItem({{ $stock->id }})" wire:confirm="Hapus item ini dari order?" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring text-destructive hover:bg-destructive hover:text-destructive-foreground h-8 w-8" title="Hapus Item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $purchasing->status != 'approved' ? '7' : '6' }}" class="p-8 text-center text-muted-foreground">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="rounded-full bg-muted p-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-muted-foreground"><path d="m21 16-5.16-5.16a2 2 0 0 0-2.83 0l-5.16 5.16"/><circle cx="12" cy="12" r="10"/></svg>
                                        </div>
                                        <p>Belum ada item yang ditambahkan ke pesanan ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($purchasing->stocks->count() > 0)
                    <tfoot class="border-t bg-muted/50 font-medium">
                        <tr>
                            <td colspan="5" class="p-4 text-right">Total:</td>
                            <td class="p-4 text-right text-primary font-bold">Rp {{ number_format($purchasing->total_initial_price, 0, ',', '.') }}</td>
                            @if($purchasing->status != 'approved')
                            <td></td>
                            @endif
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form Add Item -->
    <div x-data="{ show: false }" x-show="show" @open-modal.window="if ($event.detail[0] === 'add-item-modal') show = true" @close-modal.window="if ($event.detail[0] === 'add-item-modal') show = false" @keydown.escape.window="show = false" class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0" style="display: none;">
        <div class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[state=closed]:slide-out-to-left-1/2 data-[state=closed]:slide-out-to-top-[48%] data-[state=open]:slide-in-from-left-1/2 data-[state=open]:slide-in-from-top-[48%] sm:rounded-lg">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">Tambah Item Barang</h2>
                <p class="text-sm text-muted-foreground">Pilih produk dan tentukan jumlah serta harganya.</p>
            </div>
            
            <form wire:submit.prevent="addItem">
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2 relative">
                        <label for="searchProduct" class="text-sm font-medium leading-none">Cari Produk *</label>
                        <input wire:model.live.debounce.300ms="searchProduct" type="text" id="searchProduct" placeholder="Ketik nama, SKU, atau barcode..." class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                        
                        @if(count($products) > 0)
                            <div class="absolute z-10 w-full mt-16 bg-popover text-popover-foreground rounded-md border shadow-md">
                                <ul class="max-h-60 overflow-auto p-1">
                                    @foreach($products as $product)
                                        <li wire:click="selectProduct({{ $product->id }})" class="relative flex w-full cursor-pointer select-none items-center rounded-sm px-2 py-2 text-sm outline-none hover:bg-accent hover:text-accent-foreground">
                                            <div class="flex flex-col">
                                                <span class="font-medium">{{ $product->name }}</span>
                                                <span class="text-xs text-muted-foreground">SKU: {{ $product->sku }} | Harga Beli: Rp {{ number_format($product->initial_price, 0, ',', '.') }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @error('product_id') <span class="text-[0.8rem] font-medium text-destructive">Silakan pilih produk dari daftar.</span> @enderror
                    </div>
                    
                    @if($product_id)
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <label for="stock_qty" class="text-sm font-medium leading-none">Jumlah (Qty) *</label>
                            <input wire:model="stock_qty" type="number" id="stock_qty" min="1" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                            @error('stock_qty') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid gap-2">
                            <label for="expired" class="text-sm font-medium leading-none">Tgl Kadaluarsa (Opsional)</label>
                            <input wire:model="expired" type="date" id="expired" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                            @error('expired') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <label for="initial_price" class="text-sm font-medium leading-none">Harga Beli Satuan *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-sm text-muted-foreground">Rp</span>
                                <input wire:model="initial_price" type="number" id="initial_price" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring pl-8">
                            </div>
                            @error('initial_price') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid gap-2">
                            <label for="selling_price" class="text-sm font-medium leading-none">Harga Jual Satuan *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-sm text-muted-foreground">Rp</span>
                                <input wire:model="selling_price" type="number" id="selling_price" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring pl-8">
                            </div>
                            @error('selling_price') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                    <button type="button" @click="show = false" class="mt-2 sm:mt-0 inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                        <span wire:loading.remove wire:target="addItem">Tambah Item</span>
                        <span wire:loading wire:target="addItem">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
            
            <button @click="show = false" wire:click="resetItemForm" class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none data-[state=open]:bg-accent data-[state=open]:text-muted-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                <span class="sr-only">Close</span>
            </button>
        </div>
    </div>
</div>
