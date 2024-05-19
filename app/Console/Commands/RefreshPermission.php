<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshPermission extends Command
{
    protected $signature = 'app:refresh-permission';

    public function handle()
    {
        Artisan::call('db:seed', [
            '--class' => 'PermissionSeeder',
        ]);
    }
}
