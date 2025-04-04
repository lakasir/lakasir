<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->renameColumn('amount', 'actual_stock');
            $table->renameColumn('amount_after_adjustment', 'missing_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->renameColumn('actual_stock', 'amount');
            $table->renameColumn('missing_stock', 'amount_after_adjustment');
        });
    }
};
