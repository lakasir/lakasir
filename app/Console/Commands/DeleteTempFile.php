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
    protected $description = 'Delete temp file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // get all files in tmp folder
        $files = Storage::disk('tmp')->files();
        foreach ($files as $file) {
            Storage::disk('tmp')->delete($file);
        }
    }
}
