<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use ZipArchive;

class AppUpdateService
{
    private ?string $url;

    public function __construct()
    {
        $this->url = config('app.update_url');
    }

    public function update()
    {
        $currentVersion = trim(file_get_contents(base_path('version.txt')));

        $response = Http::get($this->url);

        if (! $response->ok()) {
            throw new \Exception('Failed to fetch update info.');
        }

        $latest = $response->json();
        $latestVersion = ltrim($latest['tag_name'], 'v');

        if (version_compare($latestVersion, $currentVersion, '<=')) {
            return 'You are already on the latest version.';
        }

        $zipUrl = $latest['assets'][0]['browser_download_url'];
        $zipPath = storage_path('app/update.zip');

        $options = [
            'http' => [
                'header' => "User-Agent: LakasirAutoUpdater\r\n",
            ],
        ];

        $context = stream_context_create($options);

        file_put_contents($zipPath, file_get_contents($zipUrl, false, $context));

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === true) {
            $extractPath = storage_path('app/update');
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            throw new \Exception('Failed to extract zip.');
        }

        $folders = glob(storage_path('app/update/*'), GLOB_ONLYDIR);
        $updateFolder = $folders[0] ?? null;

        if (! $updateFolder) {
            throw new \Exception('Update folder not found.');
        }

        $exclude = ['.env', 'storage', 'vendor'];

        $this->copyFolder($updateFolder, base_path(), $exclude);

        unlink($zipPath);
        File::deleteDirectory(storage_path('app/update'));

        Artisan::call('migrate', [
            '--force' => true,
            '--path' => 'database/migrations/tenant',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'PermissionSeeder',
        ]);


        file_put_contents(base_path('version.txt'), $latestVersion);

        return "Update to v$latestVersion completed.";
    }

    protected function copyFolder($from, $to, $exclude = [])
    {
        foreach (File::allFiles($from) as $file) {
            $relativePath = str_replace($from.'/', '', $file->getPathname());
            foreach ($exclude as $skip) {
                if (str_starts_with($relativePath, $skip)) {
                    continue 2;
                }
            }
            $destPath = $to.'/'.$relativePath;
            File::ensureDirectoryExists(dirname($destPath));
            File::copy($file->getPathname(), $destPath);
        }
    }

    public function backupApp()
    {
        $currentVersion = app(UpdateChecker::class)->getCurrentVersion();
        $path = storage_path('app/backups/app-backup-'.$currentVersion.'.zip');
        $backupDir = dirname($path);
        if (! file_exists($backupDir)) {
            mkdir($backupDir, 0777, true);
        }

        if (! file_exists($path)) {
            touch($path);
        }

        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Could not create backup zip file.');
        }

        $base = base_path();
        $exclude = ['vendor', 'node_modules', 'storage', '.git'];

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($base, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $relativePath = str_replace($base.DIRECTORY_SEPARATOR, '', $filePath);

            if (collect($exclude)->contains(fn ($dir) => str_starts_with($relativePath, $dir))) {
                continue;
            }

            if ($file->isDir()) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }

    public function restoreApp()
    {
        $backupDir = storage_path('app/backups');
        $files = File::files($backupDir);

        $latestBackup = collect($files)
            ->filter(fn ($file) => str_ends_with($file->getFilename(), '.zip'))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->first();

        if ($latestBackup) {
            $path = $latestBackup->getRealPath();
        } else {
            throw new \Exception('No backup files found.');
        }

        $zip = new ZipArchive;
        if ($zip->open($path) !== true) {
            throw new \Exception('Could not open backup zip file.');
        }

        $zip->extractTo(base_path());
        $zip->close();

        File::delete($path);
    }
}
