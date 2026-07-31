<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('receivables.index') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-accent hover:text-accent-foreground h-9 w-9 border border-input shadow-sm bg-background">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m15 18-6-6 6-6"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Piutang #{{ $receivable->selling?->code }}</h2>
                <p class="text-muted-foreground">Detail piutang dan riwayat pembayaran cicilan.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(!$receivable->status && can('create receivable payment'))
            <button x-data @click="$dispatch('open-modal', 'payment-modal')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                Tambah Pembayaran
            </button>
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
                <h3 class="font-semibold leading-none tracking-tight mb-4 text-lg">Informasi Pelanggan & Piutang</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Pelanggan</dt>
                        <dd class="text-sm font-semibold mt-1">{{ $receivable->member?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Email</dt>
                        <dd class="text-sm font-semibold mt-1">{{ $receivable->member?->email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Telepon</dt>
                        <dd class="text-sm font-semibold mt-1">{{ $receivable->member?->phone_number ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Status Piutang</dt>
                        <dd class="mt-1">
                            @if($receivable->status)
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Lunas</span>
                            @else
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Belum Lunas</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Jatuh Tempo</dt>
                        <dd class="text-sm font-semibold mt-1">{{ \Carbon\Carbon::parse($receivable->due_date)->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-muted-foreground">Tanggal Terakhir Ditagih</dt>
                        <dd class="text-sm font-semibold mt-1">{{ $receivable->last_billing_date ? \Carbon\Carbon::parse($receivable->last_billing_date)->format('d M Y') : '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <div class="rounded-xl border bg-card text-card-foreground shadow">
            <div class="p-6">
                <h3 class="font-semibold leading-none tracking-tight mb-4 text-lg">Ringkasan Nominal</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div class="flex justify-between border-b pb-2">
                        <dt class="text-sm font-medium text-muted-foreground">Total Piutang Awal</dt>
                        <dd class="text-lg font-medium">Rp {{ number_format($receivable->total_receivable, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <dt class="text-sm font-medium text-muted-foreground">Total Dibayar</dt>
                        <dd class="text-lg font-medium text-green-600 dark:text-green-400">Rp {{ number_format($receivable->total_receivable - $receivable->rest_receivable, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between pt-2">
                        <dt class="text-base font-bold text-muted-foreground">Sisa Piutang</dt>
                        <dd class="text-2xl font-bold text-destructive">Rp {{ number_format($receivable->rest_receivable, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="rounded-xl border bg-card text-card-foreground shadow mt-6">
        <div class="flex flex-col space-y-1.5 p-6 border-b sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h3 class="font-semibold leading-none tracking-tight text-lg">Riwayat Pembayaran</h3>
                <p class="text-sm text-muted-foreground mt-1">Daftar cicilan/pembayaran yang telah dilakukan.</p>
            </div>
        </div>
        <div class="p-0">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom text-sm">
                    <thead class="[&_tr]:border-b bg-muted/50">
                        <tr class="border-b transition-colors">
                            <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Tanggal</th>
                            <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Metode Pembayaran</th>
                            <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Nominal (Rp)</th>
                            <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground w-[80px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                        @forelse($receivable->receivablePayments as $payment)
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="p-4 align-middle">
                                    {{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}
                                </td>
                                <td class="p-4 align-middle">
                                    {{ $payment->paymentMethod?->name ?? '-' }}
                                </td>
                                <td class="p-4 align-middle text-right font-medium text-green-600 dark:text-green-400">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td class="p-4 align-middle text-center">
                                    <button wire:click="deletePayment({{ $payment->id }})" wire:confirm="Yakin ingin menghapus pembayaran ini? (Sisa piutang akan kembali bertambah)" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring text-destructive hover:bg-destructive hover:text-destructive-foreground h-8 w-8" title="Hapus Pembayaran">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-muted-foreground">
                                    Belum ada pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Items from Selling -->
    <div class="rounded-xl border bg-card text-card-foreground shadow mt-6">
        <div class="flex flex-col space-y-1.5 p-6 border-b sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h3 class="font-semibold leading-none tracking-tight text-lg">Item Penjualan</h3>
                <p class="text-sm text-muted-foreground mt-1">Daftar produk pada nota penjualan ini.</p>
            </div>
        </div>
        <div class="p-0">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom text-sm">
                    <thead class="[&_tr]:border-b bg-muted/50">
                        <tr class="border-b transition-colors">
                            <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Produk</th>
                            <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Qty</th>
                            <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Harga</th>
                            <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                        @if($receivable->selling && $receivable->selling->sellingItems)
                            @forelse($receivable->selling->sellingItems as $item)
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <td class="p-4 align-middle font-medium">
                                        {{ $item->product?->name ?? 'Produk Dihapus' }}
                                    </td>
                                    <td class="p-4 align-middle text-center">
                                        {{ $item->qty }}
                                    </td>
                                    <td class="p-4 align-middle text-right">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4 align-middle text-right font-medium">
                                        Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-muted-foreground">
                                        Tidak ada item.
                                    </td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Payment -->
    <div x-data="{ show: false }" x-show="show" @open-modal.window="if ($event.detail[0] === 'payment-modal') show = true" @close-modal.window="if ($event.detail[0] === 'payment-modal') show = false" @keydown.escape.window="show = false" class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0" style="display: none;">
        <div class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-md translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[state=closed]:slide-out-to-left-1/2 data-[state=closed]:slide-out-to-top-[48%] data-[state=open]:slide-in-from-left-1/2 data-[state=open]:slide-in-from-top-[48%] sm:rounded-lg">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">Catat Pembayaran Piutang</h2>
                <p class="text-sm text-muted-foreground">Sisa Piutang: <strong class="text-primary">Rp {{ number_format($receivable->rest_receivable, 0, ',', '.') }}</strong></p>
            </div>
            
            <form wire:submit.prevent="processPayment">
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <label for="payment_method_id" class="text-sm font-medium leading-none">Metode Pembayaran *</label>
                        <select wire:model="payment_method_id" id="payment_method_id" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                            <option value="">Pilih Metode</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>
                        @error('payment_method_id') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid gap-2">
                        <label for="amount" class="text-sm font-medium leading-none">Nominal Pembayaran *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-sm text-muted-foreground">Rp</span>
                            <input wire:model="amount" type="number" id="amount" max="{{ $receivable->rest_receivable }}" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring pl-8">
                        </div>
                        @error('amount') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid gap-2">
                        <label for="date" class="text-sm font-medium leading-none">Tanggal *</label>
                        <input wire:model="date" type="date" id="date" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                        @error('date') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                    <button type="button" @click="show = false" class="mt-2 sm:mt-0 inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                        <span wire:loading.remove wire:target="processPayment">Bayar</span>
                        <span wire:loading wire:target="processPayment">Memproses...</span>
                    </button>
                </div>
            </form>
            
            <button @click="show = false" wire:click="resetPaymentForm" class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none data-[state=open]:bg-accent data-[state=open]:text-muted-foreground">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                <span class="sr-only">Close</span>
            </button>
        </div>
    </div>
</div>
