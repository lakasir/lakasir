<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class Update extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.tenant.pages.update';

    protected static ?string $title = '';

    public ?string $currentVersion;

    public ?string $latestVersion;

    public array $changelog = [];

    public bool $updateAvailable = false;

    public function mount()
    {
        $updateChecker = app(\App\Services\UpdateChecker::class);

        $this->currentVersion = $updateChecker->getCurrentVersion();
        $this->updateAvailable = $updateChecker->isUpdateAvailable();
        $this->latestVersion = $updateChecker->getLatestVersion();
        $this->changelog = $updateChecker->getChangelogLines();
    }
}
