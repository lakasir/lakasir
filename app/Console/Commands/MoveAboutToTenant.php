<?php

namespace App\Console\Commands;

use App\Models\Tenants\About;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MoveAboutToTenant extends Command
{
    protected $signature = 'app:move-about-to-tenant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! Schema::hasTable('tenant_users') && ! Schema::hasTable('abouts')) {
            return;
        }
        // $tenantUser = DB::connection('mysql')
        //     ->table('tenant_users')
        //     ->select('id')
        //     ->where('tenant_id', tenant()->id)
        //     ->first();
        // $aboutFromCentral = DB::connection('mysql')
        //     ->table('abouts')
        //     ->select('*')
        //     ->where('tenant_user_id', $tenantUser->id)
        //     ->first();
        // if ($aboutFromCentral) {
        //     $about = new About();
        //     $about->fill((array) $aboutFromCentral);
        //     $about->save();
        // }
    }
}
