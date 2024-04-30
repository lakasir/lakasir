<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        // $files = Storage::disk('tmp')->files();
        // foreach ($files as $file) {
        //     Storage::disk('tmp')->delete($file);
        // }
    }
}
