<?php

namespace App\Console\Commands;

use App\Models\Tenants\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FillIsOwnerToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-is-owner-to-users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! Schema::hasTable('tenant_user')) {
            return;
        }
        $tenantUser = DB::connection('mysql')
            ->table('tenant_users')
            ->select('email')
            ->where('tenant_id', tenant()->id)
            ->first();
        if ($tenantUser) {
            User::query()
                ->where('email', $tenantUser->email)
                ->update([
                    'is_owner' => true,
                ]);
        }
    }
}
