<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateDatabase extends Command
{
    protected $signature = 'migrates';

    public function handle()
    {
        Artisan::call('migrate');
    }
}
