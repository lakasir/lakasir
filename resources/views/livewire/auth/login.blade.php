<div class="w-full max-w-sm sm:max-w-md animate-in fade-in zoom-in-95 duration-300">
    <x-ui.card class="border-2">
        <x-slot:header>
            <div class="flex flex-col items-center">
                <img src="{{ asset('assets/logo/image.png') }}" alt="Lakasir Logo" class="h-10 w-auto object-contain mb-4">
                <h1 class="text-xl font-bold tracking-tight">Selamat Datang</h1>
                <p class="text-sm text-muted-foreground mt-1">Masuk ke akun kasir Anda</p>
            </div>
        </x-slot:header>

        <form wire:submit="authenticate" class="space-y-4">
            
            @if ($errors->has('email'))
                <div class="p-3 bg-destructive/10 text-destructive text-sm rounded-md border border-destructive/20 font-medium">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <div class="space-y-2">
                <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Email Address
                </label>
                <x-ui.input 
                    wire:model="email" 
                    id="email" 
                    type="email" 
                    placeholder="nama@toko.com" 
                    required 
                    autofocus 
                />
            </div>
            
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label for="password" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                        Password
                    </label>
                </div>
                <x-ui.input 
                    wire:model="password" 
                    id="password" 
                    type="password" 
                    placeholder="••••••••" 
                    required 
                />
            </div>

            <div class="flex items-center space-x-2 pt-2">
                <input 
                    wire:model="remember" 
                    type="checkbox" 
                    id="remember" 
                    class="h-4 w-4 rounded border-input bg-background text-primary ring-offset-background focus:ring-2 focus:ring-ring"
                >
                <label for="remember" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 cursor-pointer select-none">
                    Ingat Saya
                </label>
            </div>

            <div class="pt-4">
                <x-ui.button type="submit" class="w-full relative">
                    <span wire:loading.remove wire:target="authenticate">Masuk ke Sistem</span>
                    <span wire:loading wire:target="authenticate" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
    
    <p class="text-center text-xs text-muted-foreground mt-6 font-medium">
        &copy; {{ date('Y') }} Lakasir POS. Shadcn Edition.
    </p>
</div>
