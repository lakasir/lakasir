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
            $table->after('member_id', function ($table) {
                $table->foreignId('cash_drawer_id')->nullable()->constrained('cash_drawers');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellings', function (Blueprint $table) {
                $table->dropForeign(['cash_drawer_id']);
            $table->dropColumn('cash_drawer_id');
        });
    }
};
