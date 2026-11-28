<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Pennant flags used to share one global scope. They now resolve per
     * company, so every shop starts from the flags the install had.
     */
    public function up(): void
    {
        $global = DB::table('features')->where('scope', '__laravel_null')->get();

        if ($global->isEmpty()) {
            return;
        }

        DB::table('companies')->orderBy('id')->pluck('id')->each(function (int $companyId) use ($global) {
            $scope = Company::class.'|'.$companyId;

            foreach ($global as $feature) {
                DB::table('features')->insertOrIgnore([
                    'name' => $feature->name,
                    'scope' => $scope,
                    'value' => $feature->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        DB::table('features')->where('scope', '__laravel_null')->delete();
    }

    public function down(): void
    {
        // One-way: per-shop values cannot be folded back into one.
    }
};
