<?php

namespace App\Console\Commands;

use App\Models\Tenants\UploadedFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SwitchDiskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:switch-disk {from} {to} {--dry-run : Show what would be changed without executing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Switch uploaded files from one disk to another (e.g., public -> s3)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $from = $this->argument('from');
        $to = $this->argument('to');
        $dryRun = $this->option('dry-run');

        $fromDisk = Storage::disk($from);
        $toDisk = Storage::disk($to);

        $files = UploadedFile::where('disk', $from)->get();

        if ($files->isEmpty()) {
            $this->info("No files found on disk '{$from}'.");

            return self::SUCCESS;
        }

        $this->info("Found {$files->count()} file(s) on disk '{$from}'.");
        $copied = 0;
        $failed = 0;

        foreach ($files as $file) {
            $relativePath = $file->relative_path;

            if (!$relativePath) {
                $this->warn("  Skipping file ID {$file->id}: no relative_path set.");
                $failed++;
                continue;
            }

            if (!$fromDisk->exists($relativePath)) {
                $this->warn("  Skipping file ID {$file->id} ({$relativePath}): not found on source disk.");
                $failed++;
                continue;
            }

            if ($dryRun) {
                $this->line("  Would copy: {$relativePath} (disk: {$from} → {$to})");
                $copied++;
                continue;
            }

            try {
                $stream = $fromDisk->readStream($relativePath);
                if ($stream === false) {
                    $this->error("  Failed to open stream for {$relativePath}");
                    $failed++;
                    continue;
                }

                try {
                    $toDisk->writeStream($relativePath, $stream);
                } finally {
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                }

                if (config("filesystems.disks.{$to}.driver") === 's3') {
                    $toDisk->setVisibility($relativePath, 'public');
                }

                $file->update(['disk' => $to]);

                $fromDisk->delete($relativePath);

                $this->line("  Copied: {$relativePath} (disk: {$from} → {$to})");
                $copied++;
            } catch (\Exception $e) {
                $this->error("  Failed to copy {$relativePath}: {$e->getMessage()}");
                $failed++;
            }
        }

        if ($dryRun) {
            $this->info("\nDRY RUN: {$copied} file(s) would be copied. No changes made.");
        } else {
            $this->info("\nDone: {$copied} file(s) copied, {$failed} failed.");
        }

        return self::SUCCESS;
    }
}
