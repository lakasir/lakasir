<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Pembelian</h2>
            <p class="text-muted-foreground">Kelola pemesanan dan riwayat pembelian barang dari supplier.</p>
        </div>
        <div class="flex items-center gap-2">
            @can('create purchasing')
                <button wire:click="openCreateModal" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Order Baru
                </button>
            @endcan
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

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari nomor order/supplier..." class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-8">
            </div>
            <select wire:model.live="statusFilter" class="flex h-9 w-full sm:w-48 items-center justify-between rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $status)
                    <option value="{{ $status }}">{{ Str::title($status) }}</option>
                @endforeach
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

    <div class="rounded-md border">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Supplier</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Metode Bayar</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">No. Order</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                        <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Jumlah Item</th>
                        <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Status Lunas</th>
                        <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Status Order</th>
                        <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse($purchasings as $purchasing)
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle font-medium">
                                {{ $purchasing->supplier?->name ?? '-' }}
                            </td>
                            <td class="p-4 align-middle">
                                {{ $purchasing->paymentMethod?->name ?? '-' }}
                            </td>
                            <td class="p-4 align-middle">
                                {{ $purchasing->number }}
                            </td>
                            <td class="p-4 align-middle">
                                {{ \Carbon\Carbon::parse($purchasing->date)->format('d M Y') }}
                            </td>
                            <td class="p-4 align-middle text-center">
                                {{ $purchasing->stocks_count }}
                            </td>
                            <td class="p-4 align-middle text-center">
                                @if($purchasing->payment_status)
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-100/80">
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80">
                                        Belum
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 align-middle text-center">
                                @php
                                    $statusColor = match($purchasing->status) {
                                        'pending' => 'bg-secondary text-secondary-foreground',
                                        'reviewing' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        default => 'bg-secondary text-secondary-foreground'
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent {{ $statusColor }} hover:opacity-80">
                                    {{ Str::title($purchasing->status) }}
                                </span>
                            </td>
                            <td class="p-4 align-middle text-right">
                                <div class="flex items-center justify-end gap-2" x-data="{ menuOpen: false }">
                                    <a href="{{ route('purchasings.show', $purchasing->id) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-8 w-8" title="Lihat/Edit Item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    
                                    <div class="relative">
                                        <button @click="menuOpen = !menuOpen" @click.outside="menuOpen = false" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-accent hover:text-accent-foreground h-8 w-8">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                        </button>
                                        
                                        <div x-show="menuOpen" x-transition.opacity class="absolute right-0 top-10 z-50 min-w-[8rem] overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-md" style="display: none;">
                                            @can('update purchasing')
                                            <button wire:click="openEditModal({{ $purchasing->id }})" class="relative flex w-full cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground">
                                                Edit Detail
                                            </button>
                                            @endcan
                                            <button wire:click="togglePaymentStatus({{ $purchasing->id }})" class="relative flex w-full cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground">
                                                Tandai {{ $purchasing->payment_status ? 'Belum Lunas' : 'Lunas' }}
                                            </button>
                                            @if($purchasing->status != 'approved')
                                                <div class="h-px bg-muted my-1"></div>
                                                <div class="px-2 py-1.5 text-xs font-semibold text-muted-foreground">Update Status</div>
                                                @if($purchasing->status != 'pending')
                                                <button wire:click="updatePurchasingStatus({{ $purchasing->id }}, 'pending')" class="relative flex w-full cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground">
                                                    Set Pending
                                                </button>
                                                @endif
                                                @if($purchasing->status != 'reviewing')
                                                <button wire:click="updatePurchasingStatus({{ $purchasing->id }}, 'reviewing')" class="relative flex w-full cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground">
                                                    Set Reviewing
                                                </button>
                                                @endif
                                                @if(can('approve purchasing'))
                                                <button wire:click="updatePurchasingStatus({{ $purchasing->id }}, 'approved')" class="relative flex w-full cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors text-green-600 hover:bg-green-100 hover:text-green-700 dark:text-green-400 dark:hover:bg-green-900/30">
                                                    Approve Order
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-muted-foreground">
                                Tidak ada data pembelian ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $purchasings->links() }}
    </div>

    <!-- Modal Form -->
    <div x-data="{ show: false }" x-show="show" @open-modal.window="if ($event.detail[0] === 'purchasing-modal') show = true" @close-modal.window="if ($event.detail[0] === 'purchasing-modal') show = false" @keydown.escape.window="show = false" class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0" style="display: none;">
        <div class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[state=closed]:slide-out-to-left-1/2 data-[state=closed]:slide-out-to-top-[48%] data-[state=open]:slide-in-from-left-1/2 data-[state=open]:slide-in-from-top-[48%] sm:rounded-lg">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">{{ $isEdit ? 'Edit Pembelian' : 'Order Pembelian Baru' }}</h2>
                <p class="text-sm text-muted-foreground">
                    {{ $isEdit ? 'Ubah informasi pembelian di bawah ini.' : 'Buat order pembelian baru untuk diteruskan ke penambahan stok.' }}
                </p>
            </div>
            
            <form wire:submit.prevent="save">
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <label for="supplier_id" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Supplier *</label>
                        <select wire:model="supplier_id" id="supplier_id" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Pilih Supplier...</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid gap-2">
                        <label for="payment_method_id" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Metode Pembayaran *</label>
                        <select wire:model="payment_method_id" id="payment_method_id" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="">Pilih Metode Pembayaran...</option>
                            @foreach($paymentMethods as $pm)
                                <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                            @endforeach
                        </select>
                        @error('payment_method_id') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <label for="date" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Tanggal Order *</label>
                            <input wire:model="date" type="date" id="date" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                            @error('date') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid gap-2">
                            <label for="due_date" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Jatuh Tempo *</label>
                            <input wire:model="due_date" type="date" id="due_date" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                            @error('due_date') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="grid gap-2">
                        <label for="image" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Bukti / Nota (Opsional)</label>
                        <input wire:model="image" type="file" id="image" accept="image/*" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                        @error('image') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                    <button type="button" @click="show = false" class="mt-2 sm:mt-0 inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                        <span wire:loading.remove wire:target="save">
                            {{ $isEdit ? 'Simpan Perubahan' : 'Buat Order' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
            
            <button @click="show = false" class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none data-[state=open]:bg-accent data-[state=open]:text-muted-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                <span class="sr-only">Close</span>
            </button>
        </div>
    </div>
</div>
