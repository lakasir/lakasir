<x-filament::page>
    <div class="space-y-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold tracking-tight">Software Update</h2>
            <p class="text-sm text-gray-500">Manage your version and keep Lakasir up to date.</p>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Current Version</p>
                    <p class="font-mono font-semibold text-lg text-primary-600 dark:text-primary-400">
                        v{{ $currentVersion }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Latest Version</p>
                    <p class="font-mono font-semibold text-lg">
                        v{{ $latestVersion }}

                        @if ($updateAvailable)
                            <span
                                class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                <x-heroicon-o-arrow-up class="w-4 h-4 mr-1" />
                                Update Available
                            </span>
                        @else
                            <span
                                class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                Up to Date
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if (!empty($changelog))
            <div x-data="{ expanded: false }" class="bg-white dark:bg-gray-900 rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Changelog</h3>

                <div class="prose dark:prose-invert max-w-none">
                    {!! \Illuminate\Support\Str::markdown(
                        implode(
                            "\n",
                            collect($changelog)->when(!($showAll = false), fn($lines) => $lines->take(20))->toArray(),
                        ),
                    ) !!}
                </div>

                @if (count($changelog) > 20)
                    <div x-show="!expanded" class="mt-4">
                        <button x-on:click="expanded = true" class="text-primary-600 hover:underline text-sm">
                            Read more
                        </button>
                    </div>

                    <div x-show="expanded" x-cloak class="prose dark:prose-invert max-w-none mt-4">
                        {!! \Illuminate\Support\Str::markdown(implode("\n", collect($changelog)->skip(20)->toArray())) !!}
                        <button x-on:click="expanded = false"
                            class="text-primary-600 hover:underline text-sm block mt-4">
                            Show less
                        </button>
                    </div>
                @endif
            </div>
        @endif


        @if ($updateAvailable)
            @if (can('can update app'))
                <form method="POST" action="">
                    @csrf
                    <x-filament::button color="primary" icon="heroicon-o-arrow-down-tray" type="submit">
                        Download & Install v{{ $latestVersion }}
                    </x-filament::button>
                </form>
            @endif
        @else
            <x-filament::button disabled>
                You're on the latest version
            </x-filament::button>
        @endif
    </div>
</x-filament::page>
