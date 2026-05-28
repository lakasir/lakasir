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
        Schema::table('debt_items', function (Blueprint $table) {
            $table->rename('receivable_items');
        });
        Schema::table('receivable_items', function (Blueprint $table) {
            $table->renameColumn('debt_id', 'receivable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receivable_items', function (Blueprint $table) {
            $table->rename('debt_items');
        });
        Schema::table('debt_items', function (Blueprint $table) {
            $table->renameColumn('receivable_id', 'debt_id');
        });
    }
};
