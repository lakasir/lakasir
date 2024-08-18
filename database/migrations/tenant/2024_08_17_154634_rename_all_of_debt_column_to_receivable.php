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
        Schema::table('receivables', function (Blueprint $table) {
            $table->renameColumn('total_debt', 'total_receivable');
            $table->renameColumn('rest_debt', 'rest_receivable');
        });
        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->renameColumn('last_debt', 'last_receivable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receivables', function (Blueprint $table) {
            $table->renameColumn('total_receivable', 'total_debt');
            $table->renameColumn('rest_receivable', 'rest_debt');
        });
        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->renameColumn('last_receivable', 'last_debt');
        });
    }
};
