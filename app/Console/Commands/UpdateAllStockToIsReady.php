<?php

namespace App\Console\Commands;

use App\Models\Tenants\Stock;
use Illuminate\Console\Command;

class UpdateAllStockToIsReady extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-all-stock-to-is-ready';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Stock::query()->update(['is_ready' => true]);
    }
}
