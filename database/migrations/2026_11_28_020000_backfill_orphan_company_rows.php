<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'abouts', 'cart_items', 'cash_drawers', 'categories', 'payment_methods',
        'price_units', 'printers', 'product_images', 'products', 'profiles',
        'purchasings', 'receivable_items', 'receivable_payments', 'receivables',
        'secure_initial_prices', 'selling_details', 'sellings', 'stock_opname_items',
        'stock_opnames', 'stocks', 'suppliers', 'tables', 'uploaded_files', 'vouchers',
    ];

    public function up(): void
    {
        if (DB::table('companies')->exists() || ! $this->hasOrphanRows()) {
            return;
        }

        $companyId = DB::table('companies')->insertGetId([
            'name' => DB::table('abouts')->value('shop_name') ?? 'Lakasir',
            'enabled' => true,
            'provisioned_by' => 'local',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'company_id')) {
                DB::table($table)->where('company_id', 0)->update(['company_id' => $companyId]);
            }
        }

        DB::table('roles')->whereNull('company_id')->update(['company_id' => $companyId]);
        DB::table('model_has_roles')->update(['company_id' => $companyId]);
        DB::table('model_has_permissions')->update(['company_id' => $companyId]);

        DB::table('features')
            ->where('scope', '__laravel_null')
            ->update(['scope' => Company::class.'|'.$companyId]);

        $rows = DB::table('users')->pluck('id')->map(fn (int $id) => [
            'user_id' => $id,
            'company_id' => $companyId,
            'is_owner' => true,
        ])->all();

        DB::table('user_companies')->insertOrIgnore($rows);
    }

    private function hasOrphanRows(): bool
    {
        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'company_id') && DB::table($table)->where('company_id', 0)->exists()) {
                return true;
            }
        }

        return DB::table('users')->exists()
            || DB::table('roles')->whereNull('company_id')->exists()
            || DB::table('model_has_roles')->exists()
            || DB::table('model_has_permissions')->exists()
            || DB::table('features')->where('scope', '__laravel_null')->exists();
    }

    public function down(): void
    {
        // One-way: rows a shop really owns cannot be told apart from orphans.
    }
};
