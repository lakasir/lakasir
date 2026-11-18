<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class VersionSyncCommand extends Command
{
    protected $signature = 'app:version-sync';

    protected $description = 'Sync version.txt with the latest git tag';

    public function handle(): int
    {
        $hasGitMetadata = is_dir(base_path('.git'));

        if (! $hasGitMetadata) {
            $this->warn('Git metadata is unavailable; leaving existing version.txt untouched.');

            return Command::SUCCESS;
        }

        $process = new Process(['git', 'describe', '--tags', '--abbrev=0']);
        $process->setWorkingDirectory(base_path());
        $process->run();

        $tag = trim($process->getOutput());

        if ($tag !== '') {
            file_put_contents(base_path('version.txt'), $tag);
            $this->info("version.txt synced to: {$tag}");

            return Command::SUCCESS;
        }

        if (! $process->isSuccessful()) {
            $errorOutput = strtolower(trim($process->getErrorOutput()));

            if (! str_contains($errorOutput, 'no names found')) {
                $this->error('Failed to resolve the latest Git tag.');

                return Command::FAILURE;
            }
        }

        $version = 'Development';
        file_put_contents(base_path('version.txt'), $version);
        $this->info("version.txt synced to: {$version}");

        return Command::SUCCESS;
    }
}
