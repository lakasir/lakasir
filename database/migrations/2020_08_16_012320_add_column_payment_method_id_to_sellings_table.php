<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPaymentMethodIdToSellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sellings', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->references('id')->on('payment_methods')->after('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sellings', function (Blueprint $table) {
            $table->dropForeign('sellings_payment_method_id_foreign');
        });
    }
}
