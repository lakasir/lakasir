<?php

namespace App\Console\Commands;

use App\Services\AppUpdateService;
use Illuminate\Console\Command;

class RestoreApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restore-app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore the app from a backup';

    /**
     * Execute the console command.
     */
    public function handle(AppUpdateService $appUpdateService)
    {
        try {
            $message = $appUpdateService->restoreApp();
            $this->info($message);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
