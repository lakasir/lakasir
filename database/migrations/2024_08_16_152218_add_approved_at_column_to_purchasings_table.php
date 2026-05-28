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
        Schema::table('purchasings', function (Blueprint $table) {
            $table->date('date')->change();
            $table->date('due_date')->change();
            $table->dateTime('approved_at')->nullable()->after('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasings', function (Blueprint $table) {
            $table->dateTime('date')->change();
            $table->dateTime('due_date')->change();
            $table->dropColumn('approved_at');
        });
    }
};
