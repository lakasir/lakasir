<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnStocksToFifoRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('current_stock');
            $table->dropColumn('last_stock');
            $table->double('amount')->after('item_id');
            $table->foreignId('price_id')->references('id')->on('prices')->after('item_id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->double('last_stock')->nullable()->after('item_id');
            $table->double('current_stock')->nullable()->after('last_stock');
            $table->dropColumn('amount');
        });
    }
}
