<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrateTestingDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-testing-database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::setDefaultConnection('testing');
        Artisan::call('migrate:refresh');
    }
}
