<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('stock-opnames.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-accent hover:text-accent-foreground h-9 w-9 border border-input shadow-sm bg-background">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Dokumen SO: {{ $stockOpname->number }}</h2>
                <p class="text-muted-foreground">Detail dokumen penyesuaian stok dan daftar item produk.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($stockOpname->status != 'approved')
                @if($stockOpname->status != 'pending')
                <button wire:click="updateStatus('pending')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                    Set Pending
                </button>
                @endif
                @if($stockOpname->status != 'reviewing')
                <button wire:click="updateStatus('reviewing')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                    Set Reviewing
                </button>
                @endif
                @if(can('approve stock opname'))
                <button wire:click="updateStatus('approved')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-green-600 text-white shadow hover:bg-green-600/90 h-9 px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><path d="M20 6 9 17l-5-5"/></svg>
                    Approve SO
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
                <h3 class="font-semibold leading-none tracking-tight mb-4 text-lg">Informasi Dokumen</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">PIC</dt>
                        <dd class="text-sm font-semibold mt-1">{{ $stockOpname->pic }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Oleh (User)</dt>
                        <dd class="text-sm font-semibold mt-1">{{ $stockOpname->user?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Tanggal Pelaksanaan</dt>
                        <dd class="text-sm font-semibold mt-1">{{ \Carbon\Carbon::parse($stockOpname->date)->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Tanggal Disetujui</dt>
                        <dd class="text-sm font-semibold mt-1">{{ $stockOpname->approved_at ? \Carbon\Carbon::parse($stockOpname->approved_at)->format('d M Y H:i') : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Status Dokumen</dt>
                        <dd class="mt-1">
                            @php
                                $statusColor = match($stockOpname->status) {
                                    'pending' => 'bg-secondary text-secondary-foreground',
                                    'reviewing' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                    'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    default => 'bg-secondary text-secondary-foreground'
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $statusColor }}">
                                {{ Str::title($stockOpname->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Items Section -->
    <div class="rounded-xl border bg-card text-card-foreground shadow mt-6">
        <div class="flex flex-col space-y-1.5 p-6 border-b sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h3 class="font-semibold leading-none tracking-tight text-lg">Item Penyesuaian</h3>
                <p class="text-sm text-muted-foreground mt-1">Daftar produk yang disesuaikan stoknya pada sesi ini.</p>
            </div>
            @if($stockOpname->status != 'approved')
            <button x-data @click="$dispatch('open-modal', 'add-item-modal')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Tambah/Scan Item
            </button>
            @endif
        </div>
        <div class="p-0">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom text-sm">
                    <thead class="[&_tr]:border-b bg-muted/50">
                        <tr class="border-b transition-colors">
                            <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Produk</th>
                            <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Alasan (Tipe)</th>
                            <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Stok Sistem</th>
                            <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Stok Aktual</th>
                            <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Selisih</th>
                            <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Bukti</th>
                            @if($stockOpname->status != 'approved')
                            <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground w-[80px]">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                        @forelse($stockOpname->stockOpnameItems as $item)
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="p-4 align-middle font-medium">
                                    {{ $item->product->name }}
                                </td>
                                <td class="p-4 align-middle">
                                    {{ $adjustmentTypes[$item->adjustment_type] ?? $item->adjustment_type }}
                                </td>
                                <td class="p-4 align-middle text-center font-medium">
                                    {{ $item->current_stock }}
                                </td>
                                <td class="p-4 align-middle text-center font-bold text-primary">
                                    {{ $item->actual_stock }}
                                </td>
                                <td class="p-4 align-middle text-center">
                                    @if($item->missing_stock > 0)
                                        <span class="text-red-600 font-medium">-{{ $item->missing_stock }}</span>
                                    @elseif($item->missing_stock < 0)
                                        <span class="text-green-600 font-medium">+{{ abs($item->missing_stock) }}</span>
                                    @else
                                        <span class="text-muted-foreground">0</span>
                                    @endif
                                </td>
                                <td class="p-4 align-middle text-center">
                                    @if($item->attachment)
                                        <a href="{{ Storage::url($item->attachment) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:underline">Lihat</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                @if($stockOpname->status != 'approved')
                                <td class="p-4 align-middle text-center">
                                    <button wire:click="removeItem({{ $item->id }})" wire:confirm="Hapus item ini dari dokumen?" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring text-destructive hover:bg-destructive hover:text-destructive-foreground h-8 w-8" title="Hapus Item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $stockOpname->status != 'approved' ? '7' : '6' }}" class="p-8 text-center text-muted-foreground">
                                    Tidak ada item penyesuaian stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form Add Item -->
    <div x-data="{ show: false }" x-show="show" @open-modal.window="if ($event.detail[0] === 'add-item-modal') show = true" @close-modal.window="if ($event.detail[0] === 'add-item-modal') show = false" @keydown.escape.window="show = false" class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0" style="display: none;">
        <div class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[state=closed]:slide-out-to-left-1/2 data-[state=closed]:slide-out-to-top-[48%] data-[state=open]:slide-in-from-left-1/2 data-[state=open]:slide-in-from-top-[48%] sm:rounded-lg">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">Tambah Item Penyesuaian</h2>
                <p class="text-sm text-muted-foreground">Pilih produk dan masukkan jumlah stok aktual di lapangan.</p>
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
                                                <span class="text-xs text-muted-foreground">SKU: {{ $product->sku }} | Stok Sistem: {{ $product->stock }}</span>
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
                            <label class="text-sm font-medium leading-none text-muted-foreground">Stok Sistem</label>
                            <input wire:model="current_stock" type="text" readonly class="flex h-9 w-full rounded-md border border-input bg-muted px-3 py-1 text-sm shadow-sm cursor-not-allowed">
                        </div>
                        
                        <div class="grid gap-2">
                            <label for="actual_stock" class="text-sm font-medium leading-none text-primary">Stok Aktual *</label>
                            <input wire:model.live.debounce.500ms="actual_stock" type="number" id="actual_stock" class="flex h-9 w-full rounded-md border border-primary bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring font-bold">
                            @error('actual_stock') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <label class="text-sm font-medium leading-none text-muted-foreground">Selisih (Stok Hilang/Lebih)</label>
                            <input wire:model="missing_stock" type="text" readonly class="flex h-9 w-full rounded-md border border-input bg-muted px-3 py-1 text-sm shadow-sm cursor-not-allowed">
                        </div>
                        
                        <div class="grid gap-2">
                            <label for="adjustment_type" class="text-sm font-medium leading-none">Alasan / Tipe Penyesuaian *</label>
                            <select wire:model="adjustment_type" id="adjustment_type" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                                @foreach($adjustmentTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('adjustment_type') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="grid gap-2">
                        <label for="attachment" class="text-sm font-medium leading-none">Bukti Fisik (Opsional)</label>
                        <input wire:model="attachment" type="file" id="attachment" accept="image/*" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium">
                        @error('attachment') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>
                
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                    <button type="button" @click="show = false" class="mt-2 sm:mt-0 inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                        <span wire:loading.remove wire:target="addItem">Simpan Item</span>
                        <span wire:loading wire:target="addItem">Menyimpan...</span>
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
