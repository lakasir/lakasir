<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight">Role & Hak Akses</h2>
            <p class="text-muted-foreground">Kelola jabatan dan fitur apa saja yang dapat diakses oleh masing-masing jabatan.</p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openCreateModal" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 h-4 w-4"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                Tambah Role
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
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari nama role..." class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-8">
            </div>
        </div>
    </div>

    <div class="rounded-md border bg-card">
        <div class="relative w-full overflow-auto">
            <table class="w-full caption-bottom text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-10 px-4 text-left align-middle font-medium text-muted-foreground">Nama Role</th>
                        <th class="h-10 px-4 text-center align-middle font-medium text-muted-foreground">Jumlah Pengguna</th>
                        <th class="h-10 px-4 text-right align-middle font-medium text-muted-foreground">Aksi</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    @forelse($rolesData as $roleItem)
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle font-medium">
                                {{ Str::title($roleItem->name) }}
                            </td>
                            <td class="p-4 align-middle text-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary text-xs font-bold">
                                    {{ $roleItem->users_count }}
                                </span>
                            </td>
                            <td class="p-4 align-middle text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $roleItem->id }})" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-accent hover:text-accent-foreground h-8 w-8 text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </button>
                                    
                                    <button wire:click="delete({{ $roleItem->id }})" wire:confirm="Apakah Anda yakin ingin menghapus role ini?" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring hover:bg-destructive hover:text-destructive-foreground h-8 w-8 text-destructive">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-muted-foreground">
                                Tidak ada data role ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $rolesData->links() }}
    </div>

    <!-- Modal Form -->
    <div x-data="{ show: false }" x-show="show" @open-modal.window="if ($event.detail[0] === 'role-modal') show = true" @close-modal.window="if ($event.detail[0] === 'role-modal') show = false" @keydown.escape.window="show = false" class="fixed inset-0 z-50 bg-background/80 backdrop-blur-sm data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0" style="display: none;">
        <div class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-4xl max-h-[90vh] translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 sm:rounded-lg overflow-y-auto">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left sticky top-0 bg-background pt-2 pb-4 border-b z-10">
                <h2 class="text-lg font-semibold leading-none tracking-tight">{{ $isEdit ? 'Edit Role' : 'Tambah Role Baru' }}</h2>
                <p class="text-sm text-muted-foreground">
                    Atur nama jabatan dan centang fitur yang dapat diakses.
                </p>
            </div>
            
            <form wire:submit.prevent="save">
                <div class="grid gap-6 py-4">
                    <div class="grid gap-2">
                        <label for="name" class="text-sm font-medium leading-none">Nama Jabatan / Role *</label>
                        <input wire:model="name" type="text" id="name" class="flex h-9 w-full sm:w-1/2 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" placeholder="Contoh: Kasir, SPV, Gudang">
                        @error('name') <span class="text-[0.8rem] font-medium text-destructive">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium">Pengaturan Akses Fitur (Permissions)</h3>
                            <p class="text-xs text-muted-foreground">Centang fitur yang boleh diakses oleh role ini.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($permissions as $module => $modulePermissions)
                            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                                <div class="flex flex-col space-y-1 p-4 border-b bg-muted/20">
                                    <h3 class="font-semibold text-sm leading-none tracking-tight">{{ Str::title($module) }}</h3>
                                </div>
                                <div class="p-4 pt-2 space-y-2">
                                    @foreach($modulePermissions as $permission)
                                    <div class="flex items-center space-x-2">
                                        <input wire:model="selectedPermissions" type="checkbox" id="perm_{{ $permission['id'] }}" value="{{ $permission['id'] }}" class="h-4 w-4 rounded border-primary text-primary focus:ring-primary">
                                        <label for="perm_{{ $permission['id'] }}" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 cursor-pointer">
                                            {{ Str::title($permission['action']) }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2 sticky bottom-0 bg-background py-4 border-t z-10">
                    <button type="button" @click="show = false" class="mt-2 sm:mt-0 inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                        <span wire:loading.remove wire:target="save">
                            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Role' }}
                        </span>
                        <span wire:loading wire:target="save">
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
            
            <button @click="show = false" class="absolute right-4 top-4 rounded-sm opacity-70 transition-opacity hover:opacity-100 z-20 bg-background/50 p-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                <span class="sr-only">Close</span>
            </button>
        </div>
    </div>
</div>
