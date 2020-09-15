<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBussinerDescriptionColumnToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('business_description');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->text('business_description')->after('business_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('business_description');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->text('business_description')->after('business_type');
        });
    }
}
