<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use ZipArchive;

use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;

class AppUpdateService
{
    private ?string $url;

    private ?array $artisanAfterUpdate;

    private ?array $artisanAfterRestore;

    private ?array $commandsAfterUpdate;

    /** @var callable|null */
    private $logger = null;

    public function __construct(?callable $logger = null)
    {
        $this->url = config('updater.url');

        $this->artisanAfterUpdate = config('updater.artisan_after_update') ?? [];

        $this->artisanAfterRestore = config('updater.artisan_after_restore') ?? [];

        $this->commandsAfterUpdate = config('updater.commands_after_update') ?? [];

        $this->logger = $logger;
    }

    public function update()
    {
        $logger = $this->logger;
        $log = fn ($text) => $logger ? $logger($text) : info($text);

        $log('🔍 Checking for updates...');
        $currentVersion = trim(file_get_contents(base_path('version.txt')));

        $response = Http::get($this->url);

        if (! $response->ok()) {
            throw new Exception('Failed to fetch update $log.');
        }

        $latest = $response->json();
        $latestVersion = ltrim($latest['tag_name'], 'v');

        $log('📦 Current version: '.$currentVersion);
        $log('📦 Latest version: '.$latestVersion);

        if (version_compare($latestVersion, $currentVersion, '<=')) {
            $log('✅ You are already on the latest version.');

            return;
        }
        $log('🚀 Updating from v'.$currentVersion.' to v'.$latestVersion.'...');

        $zipUrl = $latest['assets'][0]['browser_download_url'];
        $zipSize = $latest['assets'][0]['size'];
        $zipPath = storage_path('app/update.zip');
        $options = [
            'http' => [
                'header' => "User-Agent: LakasirAutoUpdater\r\n",
            ],
        ];

        $context = stream_context_create($options);

        $readStream = fopen($zipUrl, 'r', false, $context);
        if (! $readStream) {
            throw new Exception('Failed to open update ZIP stream.');
        }

        $writeStream = fopen($zipPath, 'w');
        if (! $writeStream) {
            throw new Exception('Failed to create ZIPxfile.');
        }

        $chunkSize = 1024 * 512; // 512 KB

        $progress = progress(
            label: '📥 Downloading update...',
            steps: $zipSize,
        );
        $progress->start();

        while (! feof($readStream)) {
            $buffer = fread($readStream, $chunkSize); // 512 KB chunks
            fwrite($writeStream, $buffer);
            $progress->advance(strlen($buffer));
        }
        fclose($readStream);
        fclose($writeStream);

        $progress->finish();

        $log('✅ Download completed.');

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === true) {
            $log('☕ Extracting downloaded files...');
            $extractPath = storage_path('app/update/lakasir/');
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            throw new Exception('❌ Failed to extract zip.');
        }

        $folders = glob(storage_path('app/update/*'), GLOB_ONLYDIR);
        $updateFolder = $folders[0] ?? null;

        if (! $updateFolder) {
            throw new Exception('❌ Update folder not found.');
        }

        $exclude = ['.env', 'storage'];

        $this->copyFolder($updateFolder, base_path(), $exclude, $log);

        unlink($zipPath);
        $log('🗑️ Cleaning up...');
        File::deleteDirectory(storage_path('app/update'));

        foreach ($this->artisanAfterUpdate as $key => $command) {
            $this->runArtisanCommands($key, $command);
        }

        foreach ($this->commandsAfterUpdate as $command) {
            $log('💻 Running command: '.$command);
            exec($command);
        }

        file_put_contents(base_path('version.txt'), $latestVersion);

        $log("✅ Update to v$latestVersion completed.");
        Cache::forget('update:progress');
    }

    protected function copyFolder($from, $to, $exclude, $log)
    {
        $log('📦 Copying files...');
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
        $logger = $this->logger;
        $log = fn ($text) => $logger ? $logger($text) : info($text);
        $log('📦 Backing up app...');
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
            throw new Exception('Could not create backup zip file.');
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
        $log('✅ App backed up successfully');
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
            throw new Exception('No backup files found.');
        }

        $zip = new ZipArchive;
        if ($zip->open($path) !== true) {
            throw new Exception('Could not open backup zip file.');
        }

        $zip->extractTo(base_path());
        $zip->close();

        foreach ($this->artisanAfterRestore as $key => $command) {
            $this->runArtisanCommands($key, $command);
        }

        File::delete($path);
    }

    private function runArtisanCommands(mixed $key, mixed $command)
    {
        $logger = $this->logger;
        $log = fn ($text) => $logger ? $logger($text) : info($text);
        if (is_int($key)) {
            $log('💻 Running artisan command: '.$command);
            Artisan::call($command);
        }
        if (is_string($key)) {
            $log('💻 Running artisan command: '.$key);
            Artisan::call($key, $command);
        }
    }
}
