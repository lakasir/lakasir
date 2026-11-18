<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteTempFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-temp-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary uploaded files that have not been moved to permanent storage';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tmpDisk = config('filesystems.tmp_disk');
        $disk = Storage::disk($tmpDisk);

        $files = $disk->files();
        $count = 0;

        foreach ($files as $file) {
            if ($disk->lastModified($file) < now()->subDay()->timestamp) {
                $disk->delete($file);
                $count++;
            }
        }

        $this->info("Deleted {$count} temporary file(s).");

        return self::SUCCESS;
    }
}
