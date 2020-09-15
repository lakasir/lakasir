<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUserIdToTablePurchasings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchasings', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade')->after('supplier_id');
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
            $table->dropForeign('user_id');
        });
    }
}
