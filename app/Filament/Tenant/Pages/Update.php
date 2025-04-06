<?php

namespace App\Filament\Tenant\Pages;

use App\Services\AppUpdateService;
use Exception;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

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

    public function updateApp(AppUpdateService $appUpdateService)
    {
        if (! can('can update app')) {
            Notification::make()
                ->danger()
                ->title(__('You do not have an access to update the app'))
                ->send();

            return;
        }

        try {
            $backupFile = storage_path('app/backups/app-backup-'.now()->format('Ymd-His').'.zip');
            $appUpdateService->backupApp($backupFile);
            $appUpdateService->update();
            Notification::make()
                ->success()
                ->title(__('App updated successfully'))
                ->send();
        } catch (Exception $e) {
            report($e);

            Notification::make()
                ->danger()
                ->title(__('Failed to update the app'))
                ->body($e->getMessage())
                ->send();
        }
    }
}
