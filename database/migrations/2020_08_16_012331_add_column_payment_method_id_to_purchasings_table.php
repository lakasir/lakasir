<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPaymentMethodIdToPurchasingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchasings', function (Blueprint $table) {
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
        Schema::table('purchasings', function (Blueprint $table) {
            $table->dropForeign('purchasings_payment_method_id_foreign');
        });
    }
}
