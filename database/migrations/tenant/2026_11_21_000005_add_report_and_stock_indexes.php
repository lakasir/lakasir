<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Indexes for the three hot reads: report/dashboard date windows, the stock
 * read that every sale and every product card performs, and the POS search.
 *
 * MySQL already indexes foreign keys, so nothing here repeats one.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellings', function (Blueprint $table) {
            // Reports and the dashboard filter on one or the other, and both
            // together with the paid flag.
            $table->index('date');
            $table->index('created_at');
            $table->index(['is_paid', 'created_at']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            // `where product_id = ? and is_ready = 1 and type = 'in' and stock > 0`,
            // ordered by created_at (fifo/lifo) or id (normal).
            $table->index(['product_id', 'is_ready', 'type', 'stock'], 'stocks_product_available_index');
        });

        Schema::table('products', function (Blueprint $table) {
            // POS search matches the exact sku, and the grid filters show/type.
            $table->index('sku');
            $table->index(['show', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('sellings', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['is_paid', 'created_at']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropIndex('stocks_product_available_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['sku']);
            $table->dropIndex(['show', 'type']);
        });
    }
};
