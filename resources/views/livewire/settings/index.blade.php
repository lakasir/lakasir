<div x-data="{ activeTab: @entangle('activeTab') }" class="space-y-6">

    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Sidebar Tabs -->
        <aside class="w-full md:w-64 shrink-0">
            <nav class="flex space-x-2 md:flex-col md:space-x-0 md:space-y-1">
                <button @click="activeTab = 'about'" :class="{'bg-muted hover:bg-muted text-primary': activeTab === 'about', 'hover:bg-transparent hover:underline text-muted-foreground': activeTab !== 'about'}" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
                    Profil Toko
                </button>
                <button @click="activeTab = 'app'" :class="{'bg-muted hover:bg-muted text-primary': activeTab === 'app', 'hover:bg-transparent hover:underline text-muted-foreground': activeTab !== 'app'}" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
                    Aplikasi & Fitur
                </button>
                <button @click="activeTab = 'profile'" :class="{'bg-muted hover:bg-muted text-primary': activeTab === 'profile', 'hover:bg-transparent hover:underline text-muted-foreground': activeTab !== 'profile'}" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
                    Profil Pengguna
                </button>
                <button @click="activeTab = 'printer'" :class="{'bg-muted hover:bg-muted text-primary': activeTab === 'printer', 'hover:bg-transparent hover:underline text-muted-foreground': activeTab !== 'printer'}" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium transition-colors">
                    Printer POS
                </button>
            </nav>
        </aside>

        <!-- Tab Content -->
        <div class="flex-1 max-w-3xl">
            <!-- About Tab -->
            <div x-show="activeTab === 'about'" x-transition>
                <x-ui.card class="border-2 shadow-skeuo-card">
                    <x-slot:header>
                        <h3 class="font-semibold leading-none tracking-tight">Profil Toko</h3>
                        <p class="text-sm text-muted-foreground">Ubah nama toko, lokasi, dan mata uang.</p>
                    </x-slot:header>
                    <form wire:submit="saveAbout" class="space-y-4">
                        <div>
                            <x-ui.label>Nama Toko</x-ui.label>
                            <x-ui.input wire:model="about.shop_name" type="text" />
                            @error('about.shop_name') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-ui.label>Lokasi / Alamat</x-ui.label>
                            <x-ui.input wire:model="about.shop_location" type="text" />
                            @error('about.shop_location') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-ui.label>Mata Uang</x-ui.label>
                            <select wire:model="about.currency" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="IDR">IDR</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div class="pt-4">
                            <x-ui.button type="submit">Simpan Profil Toko</x-ui.button>
                        </div>
                    </form>
                </x-ui.card>
            </div>

            <!-- App Tab -->
            <div x-show="activeTab === 'app'" x-cloak x-transition>
                <x-ui.card class="border-2 shadow-skeuo-card">
                    <x-slot:header>
                        <h3 class="font-semibold leading-none tracking-tight">Aplikasi & Fitur</h3>
                        <p class="text-sm text-muted-foreground">Konfigurasi stok minimum, pajak, dan fitur flag aktif.</p>
                    </x-slot:header>
                    <form wire:submit="saveSettings" class="space-y-6">
                        <div class="space-y-4">
                            <h4 class="text-sm font-medium leading-none">Pengaturan Umum</h4>
                            <div>
                                <x-ui.label>Notifikasi Stok Minimum</x-ui.label>
                                <x-ui.input wire:model="setting.minimum_stock_nofication" type="number" />
                            </div>
                            <div>
                                <x-ui.label>Pajak Default (%)</x-ui.label>
                                <x-ui.input wire:model="setting.default_tax" type="number" />
                            </div>
                        </div>

                        <hr />

                        <div class="space-y-4">
                            <h4 class="text-sm font-medium leading-none">Feature Flags</h4>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($feature as $key => $value)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" wire:model="feature.{{ $key }}" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 capitalize">{{ str_replace('-', ' ', $key) }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4">
                            <x-ui.button type="submit">Simpan Pengaturan Aplikasi</x-ui.button>
                        </div>
                    </form>
                </x-ui.card>
            </div>

            <!-- Profile Tab -->
            <div x-show="activeTab === 'profile'" x-cloak x-transition>
                <x-ui.card class="border-2 shadow-skeuo-card">
                    <x-slot:header>
                        <h3 class="font-semibold leading-none tracking-tight">Profil Pengguna</h3>
                        <p class="text-sm text-muted-foreground">Perbarui informasi profil dan kata sandi Anda.</p>
                    </x-slot:header>
                    <form wire:submit="saveProfile" class="space-y-4">
                        <div>
                            <x-ui.label>Nama</x-ui.label>
                            <x-ui.input wire:model="profile.name" type="text" />
                            @error('profile.name') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-ui.label>Email</x-ui.label>
                            <x-ui.input wire:model="profile.email" type="email" />
                            @error('profile.email') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-ui.label>Nomor Telepon</x-ui.label>
                            <x-ui.input wire:model="profile.phone" type="text" />
                        </div>
                        <div>
                            <x-ui.label>Alamat Lengkap</x-ui.label>
                            <textarea wire:model="profile.address" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                        </div>
                        <hr />
                        <div>
                            <x-ui.label>Kata Sandi Baru (Kosongkan jika tidak diubah)</x-ui.label>
                            <x-ui.input wire:model="profile.password" type="password" />
                            @error('profile.password') <span class="text-sm text-destructive">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <x-ui.label>Konfirmasi Kata Sandi Baru</x-ui.label>
                            <x-ui.input wire:model="profile.password_confirmation" type="password" />
                        </div>

                        <div class="pt-4">
                            <x-ui.button type="submit">Simpan Profil Pengguna</x-ui.button>
                        </div>
                    </form>
                </x-ui.card>
            </div>

            <!-- Printer Tab -->
            <div x-show="activeTab === 'printer'" x-cloak x-transition x-data="printerSettings">
                <x-ui.card class="border-2 shadow-skeuo-card">
                    <x-slot:header>
                        <h3 class="font-semibold leading-none tracking-tight">Printer POS (WebUSB)</h3>
                        <p class="text-sm text-muted-foreground">Hubungkan printer thermal USB langsung dari browser.</p>
                    </x-slot:header>
                    <div class="space-y-4">
                        <div x-show="successMessage" class="p-3 text-sm text-green-800 bg-green-50 rounded-md" x-text="successMessage"></div>

                        <div>
                            <x-ui.label>Tipe Koneksi</x-ui.label>
                            <select x-model="data.driver" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                                <option value="usb">USB Thermal Printer</option>
                            </select>
                        </div>

                        <div>
                            <x-ui.label>Nama Konfigurasi Printer</x-ui.label>
                            <x-ui.input x-model="data.name" placeholder="Misal: Printer Kasir Depan" type="text" />
                        </div>

                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <x-ui.label>Device Printer Terpilih</x-ui.label>
                                <x-ui.input x-model="data.printer" readonly placeholder="Klik tombol di sebelah kanan ->" type="text" />
                            </div>
                            <x-ui.button type="button" @click="fetchTheUsb" variant="secondary" class="border-2 shadow-skeuo-btn">
                                Pindai USB
                            </x-ui.button>
                        </div>

                        <div>
                            <x-ui.label>Header Struk (Teks di atas)</x-ui.label>
                            <textarea x-model="data.header" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background"></textarea>
                        </div>

                        <div>
                            <x-ui.label>Footer Struk (Teks di bawah)</x-ui.label>
                            <textarea x-model="data.footer" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background"></textarea>
                        </div>

                        <div class="pt-4 flex gap-2">
                            <x-ui.button type="button" @click="save">Simpan Pengaturan Printer</x-ui.button>
                            <x-ui.button type="button" variant="outline" @click="test">Test Print</x-ui.button>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('printerSettings', () => ({
        data: {
            driver: 'usb',
            name: '',
            printer: '',
            printerId: '',
            header: '',
            footer: ''
        },
        successMessage: '',
        init() {
            if (localStorage.printer) {
                const storedPrinter = JSON.parse(localStorage.printer);
                this.data = { ...this.data, ...storedPrinter };
            }
        },
        async fetchTheUsb() {
            try {
                let selectedDevice = await navigator.usb.requestDevice({ filters: [] });
                await selectedDevice.open();
                await selectedDevice.selectConfiguration(1);
                await selectedDevice.claimInterface(0);
                this.data.printer = selectedDevice.productName;
                this.data.printerId = selectedDevice.vendorId;
            } catch (error) {
                console.error(error);
                alert("Gagal memindai USB Printer. Pastikan izin USB browser nyala.");
            }
        },
        save() {
            if(!this.data.printer || !this.data.name) {
                alert("Pindai printer terlebih dahulu dan isi nama konfigurasinya.");
                return;
            }
            localStorage.setItem("printer", JSON.stringify(this.data));
            this.successMessage = 'Pengaturan printer disimpan ke Local Storage.';
            setTimeout(() => this.successMessage = '', 3000);
        },
        async test() {
            if(!this.data.printerId) {
                alert("Simpan pengaturan printer terlebih dahulu.");
                return;
            }
            alert("Harap dipastikan class 'Printer' sudah tersedia di window dari core app Anda. Jika tidak, tambahkan library POS Printer.");
            try {
                // Di sini memanggil class Printer yang ada di POS (terinject via global script di app layout)
                if (typeof Printer !== 'undefined') {
                    const printer = new Printer(this.data.printerId);
                    let printerAction = printer.font('a')
                        .size(1)
                        .align('center')
                        .text('Lakasir POS Test')
                        .size(0);
                        
                    if(this.data.header) printerAction.text(this.data.header);
                    
                    printerAction.align('left')
                        .text('-------------------------------')
                        .text('Test koneksi berhasil!')
                        .text('-------------------------------')
                        .newLine()
                        .align('center');

                    if(this.data.footer) printerAction.text(this.data.footer);
                    
                    await printerAction.cut().print();
                } else {
                    console.error("Printer class is undefined.");
                }
            } catch (e) {
                console.error(e)
            }
        }
    }));
});
</script>
