<?php

namespace App\Console\Commands;

use App\Services\AppUpdateService;
use Illuminate\Console\Command;

class UpdateApp extends Command
{
    protected $signature = 'app:update';

    protected $description = 'Auto-update app from GitHub latest release';

    private AppUpdateService $updateService;

    public function __construct()
    {
        parent::__construct();
        $this->updateService = new AppUpdateService;
    }

    public function handle()
    {
        try {
            $message = $this->updateService->update();
            $this->info($message);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
