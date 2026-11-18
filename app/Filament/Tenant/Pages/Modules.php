<?php

namespace App\Filament\Tenant\Pages;

use App\Traits\HasTranslatableResource;
use Filament\Pages\Page;

class Modules extends Page
{
    use HasTranslatableResource;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $slug = 'modules-overview';

    protected static string $view = 'filament.tenant.pages.modules';

    public static function canAccess(): bool
    {
        return can('access general setting');
    }

    public static function getNavigationLabel(): string
    {
        return __('Modules');
    }

    public function getTitle(): string
    {
        return __('Modules');
    }

    /**
     * Installed modules, derived from the `modules/` directory the
     * lakasir-module runtime scans.
     *
     * @return array<int, array{name: string, package: string, title: string, version: string, provider: string, has_filament: bool}>
     */
    public function getModules(): array
    {
        return collect(getModules())
            ->map(function (string $path): array {
                $name = basename($path);
                $composer = json_decode(@file_get_contents($path.'/composer.json') ?: '{}', true) ?: [];
                $provider = "Modules\\{$name}\\{$name}ServiceProvider";

                return [
                    'name' => $name,
                    'package' => $composer['name'] ?? '-',
                    'version' => $composer['version'] ?? '-',
                    'provider' => $provider,
                    'title' => class_exists($provider) && method_exists($provider, 'getTitle')
                        ? ($provider::getTitle() ?: $name)
                        : $name,
                    'has_filament' => is_dir($path.'/src/Filament/Resources'),
                ];
            })
            ->sortBy('name')
            ->values()
            ->all();
    }
}
