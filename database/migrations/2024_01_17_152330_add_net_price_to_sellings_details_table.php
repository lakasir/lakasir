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
        Schema::table('selling_details', function (Blueprint $table) {
            $table->after('price', function (Blueprint $table) {
                $table->double('net_price')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('selling_details', function (Blueprint $table) {
            $table->dropColumn('net_price');
        });
    }
};
