<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Business tables owned by one company. The three tables whose unique keys
     * already mention company_id (members, settings, barcodes) add it in their
     * own create migration; Pennant's features are per company through their
     * scope column.
     */
    private array $tables = [
        'abouts',
        'cart_items',
        'cash_drawers',
        'categories',
        'receivables',
        'receivable_items',
        'receivable_payments',
        'payment_methods',
        'price_units',
        'printers',
        'product_images',
        'products',
        'profiles',
        'purchasings',
        'secure_initial_prices',
        'selling_details',
        'sellings',
        'stock_opname_items',
        'stock_opnames',
        'stocks',
        'suppliers',
        'tables',
        'uploaded_files',
        'vouchers',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->foreignId('company_id')->index();
            });
        }

        // A sale code is unique per shop, not per install: the earlier
        // migration created it before company_id existed.
        Schema::table('sellings', function (Blueprint $blueprint) {
            $blueprint->dropUnique(['code']);
            $blueprint->unique(['company_id', 'code']);
        });
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('company_id');
            });
        }

        Schema::table('sellings', function (Blueprint $blueprint) {
            $blueprint->dropUnique(['company_id', 'code']);
            $blueprint->unique(['code']);
        });
    }
};
