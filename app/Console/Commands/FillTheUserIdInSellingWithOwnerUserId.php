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
     */
    public function handle()
    {
        Selling::all()->each(function (Selling $selling) {
            $user = User::first();
            $selling->user()->associate($user);
            $selling->save();
        });
    }
}
