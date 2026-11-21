<?php

namespace App\Console\Commands;

use App\Models\Tenants\Selling;
use App\Models\Tenants\User;
use Illuminate\Console\Command;

class FillTheUserIdInSellingWithOwnerUserId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-the-user-id-in-selling-with-owner-user-id';

    /**
     * Execute the console command.
     *
     * The owner is read once and the sales are paged, so the command never
     * loads the whole table or queries the owner per sale.
     */
    public function handle()
    {
        $owner = User::first();

        if (! $owner) {
            $this->warn('No account to attribute the sales to.');

            return;
        }

        Selling::query()
            ->whereNull('user_id')
            ->chunkById(200, function ($sellings) use ($owner) {
                foreach ($sellings as $selling) {
                    // saveQuietly: the observer would stamp the current user.
                    $selling->user()->associate($owner);
                    $selling->saveQuietly();
                }
            });
    }
}
