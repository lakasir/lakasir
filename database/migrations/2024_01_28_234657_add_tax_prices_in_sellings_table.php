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
        Schema::table('sellings', function (Blueprint $table) {
            $table->after('total_price', function (Blueprint $table) {
                // TODO: delete this in future, and update all of double to decimal like this
                $table->decimal('tax_price', 15, 2)->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellings', function (Blueprint $table) {
            $table->dropColumn('tax_price');
        });
    }
};
