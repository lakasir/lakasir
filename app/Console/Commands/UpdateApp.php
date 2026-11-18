<?php

namespace App\Console\Commands;

use App\Services\AppUpdateService;
use Illuminate\Console\Command;

class UpdateApp extends Command
{
    protected $signature = 'app:update';

    protected $description = 'Auto-update app from GitHub latest release';

    public function handle()
    {
        try {
            $appUpdateService = new AppUpdateService();
            $appUpdateService->update();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
