<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomerTypeIdColumnToNullableInCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['customer_type_id']);
            $table->dropColumn('customer_type_id');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('customer_type_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('customer_type_id');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('customer_type_id')->references('id')->on('customer_types')->after('id')->nullable();
        });
    }
}
