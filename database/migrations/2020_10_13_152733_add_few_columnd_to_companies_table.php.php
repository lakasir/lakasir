<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFewColumndToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table)
        {
            $table->string('name')->after('id')->nullable();
            $table->text('address')->after('business_type')->nullable();
            $table->char('default_currency')->after('reg_number')->nullable();
            $table->integer('expected_max_employee')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table)
        {
            $table->dropColumn('name');
            $table->dropColumn('address');
            $table->dropColumn('default_currency');
            $table->dropColumn('expected_max_employee');
        });
    }
}
