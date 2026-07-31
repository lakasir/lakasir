<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Karyawan & Akses</h2>
            <p class="text-muted-foreground">Kelola staf, kasir, dan admin yang memiliki akses ke sistem Anda.</p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Asumsi permission: create user -->
            <button wire:click="openCreateModal" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                Tambah Karyawan
            </button>
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
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari nama atau email..." class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-8">
            </div>
        </div>
        <div class="flex items-center gap-2">
            <select wire:model.live="perPage" class="flex h-9 items-center justify-between rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>
    </div>

    <div class="rounded-md border bg-card">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Karyawan</th>
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Role / Akses</th>
                        <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Status</th>
                        <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse($users as $user)
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 shrink-0 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $user->name }}</span>
                                        <span class="text-xs text-muted-foreground">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 align-middle">
                                @if($user->is_owner)
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors border-transparent bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                        Owner
                                    </span>
                                @elseif($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors border-transparent bg-secondary text-secondary-foreground">
                                            {{ Str::title($role->name) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted-foreground italic text-xs">Belum ada role</span>
                                @endif
                            </td>
                            <td class="p-4 align-middle text-center">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors border-transparent bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Aktif
                                </span>
                            </td>
                            <td class="p-4 align-middle text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $user->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-accent hover:text-accent-foreground h-8 w-8 text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </button>
                                    
                                    @if(!$user->is_owner && $user->id !== auth()->id())
                                    <button wire:click="delete({{ $user->id }})" wire:confirm="Apakah Anda yakin ingin menghapus akun ini?" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-destructive hover:text-destructive-foreground h-8 w-8 text-destructive">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-muted-foreground">
                                Tidak ada data karyawan ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Modal Form -->
    <div x-data="{ show: false }" x-show="show" @open-modal.window="if ($event.detail[0] === 'user-modal') show = true" @close-modal.window="if ($event.detail[0] === 'user-modal') show = false" @keydown.escape.window="show = false" class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0" style="display: none;">
        <div class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-md translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 sm:rounded-lg">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">{{ $isEdit ? 'Edit Karyawan' : 'Tambah Karyawan Baru' }}</h2>
                <p class="text-sm text-muted-foreground">
                    Isi informasi akun karyawan di bawah ini.
                </p>
            </div>
            
            <form wire:submit.prevent="save">
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <label for="name" class="text-sm font-medium leading-none">Nama Lengkap *</label>
                        <input wire:model="name" type="text" id="name" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                        @error('name') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid gap-2">
                        <label for="email" class="text-sm font-medium leading-none">Alamat Email *</label>
                        <input wire:model="email" type="email" id="email" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                        @error('email') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid gap-2">
                        <label for="password" class="text-sm font-medium leading-none">Kata Sandi {{ $isEdit ? '(Opsional)' : '*' }}</label>
                        <input wire:model="password" type="password" id="password" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" placeholder="{{ $isEdit ? 'Kosongkan jika tidak ingin mengubah password' : '' }}">
                        @error('password') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid gap-2">
                        <label for="role" class="text-sm font-medium leading-none">Jabatan / Role</label>
                        <select wire:model="role" id="role" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:opacity-50" {{ $is_owner ? 'disabled' : '' }}>
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}">{{ Str::title($r->name) }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                        @if($is_owner)
                            <p class="text-xs text-amber-600 mt-1">Akun owner memiliki akses tertinggi dan tidak memerlukan role.</p>
                        @endif
                    </div>
                    
                    @if(auth()->user()->is_owner)
                    <div class="flex flex-row items-start space-x-3 space-y-0 rounded-md border p-4">
                        <div class="flex h-4 items-center">
                            <input wire:model.live="is_owner" type="checkbox" id="is_owner" class="h-4 w-4 rounded border-primary text-primary focus:ring-primary">
                        </div>
                        <div class="space-y-1 leading-none">
                            <label for="is_owner" class="text-sm font-medium leading-none cursor-pointer">
                                Jadikan sebagai Owner
                            </label>
                            <p class="text-sm text-muted-foreground">
                                Memberikan akses penuh ke semua fitur dan konfigurasi sistem tanpa batas.
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2">
                    <button type="button" @click="show = false" class="mt-2 sm:mt-0 inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                        <span wire:loading.remove wire:target="save">
                            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Karyawan' }}
                        </span>
                        <span wire:loading wire:target="save">
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
            
            <button @click="show = false" class="absolute right-4 top-4 rounded-sm opacity-70 transition-opacity hover:opacity-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                <span class="sr-only">Close</span>
            </button>
        </div>
    </div>
</div>
