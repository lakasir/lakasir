<?php

namespace App\Filament\Tenant\Pages;

use App\Jobs\RunAppUpdate;
use App\Services\AppUpdateService;
use Exception;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class Update extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.tenant.pages.update';

    protected static ?string $title = '';

    public ?string $currentVersion;

    public ?string $latestVersion;

    public array $changelog = [];

    public bool $updateAvailable = false;

    public bool $hasPreviousVersion = false;

    public string $updateLog = '';

    public function mount()
    {
        $updateChecker = app(\App\Services\UpdateChecker::class);

        $this->currentVersion = $updateChecker->getCurrentVersion();
        $this->updateAvailable = $updateChecker->isUpdateAvailable();
        $this->latestVersion = $updateChecker->getLatestVersion();
        $this->changelog = $updateChecker->getChangelogLines();
        $this->hasPreviousVersion = $this->getPreviousVersion();
    }

    public function pollLogs()
    {
        $key = "update:progress";
        $this->updateLog = Cache::get($key, '');
    }

    public function updateApp()
    {
        if (! can('can update app')) {
            Notification::make()
                ->danger()
                ->title(__('You do not have an access to update the app'))
                ->send();

            return;
        }

        try {
            dispatch(new RunAppUpdate());
        } catch (Exception $e) {
            report($e);

            Notification::make()
                ->danger()
                ->title(__('Failed to update the app'))
                ->body($e->getMessage())
                ->send();
        }
    }

    public function getPreviousVersion(): bool
    {
        $backupDirectory = storage_path('app/backups');
        $files = glob($backupDirectory.'/*.zip');

        if (empty($files)) {
            return false;
        }

        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        return file_exists($files[0]);
    }

    public function restoreApp(AppUpdateService $appUpdateService)
    {
        if (! can('can update app')) {
            Notification::make()
                ->danger()
                ->title(__('You do not have an access to update the app'))
                ->send();

            return;
        }

        try {
            $appUpdateService->restoreApp();
            Notification::make()
                ->success()
                ->title(__('App restored successfully'))
                ->send();
        } catch (Exception $e) {
            report($e);

            Notification::make()
                ->danger()
                ->title(__('Failed to restore the app'))
                ->body($e->getMessage())
                ->send();
        }
    }
}
