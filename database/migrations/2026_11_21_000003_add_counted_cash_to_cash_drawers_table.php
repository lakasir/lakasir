<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_drawers', function (Blueprint $table) {
            $table->double('counted_cash')->nullable()->after('cash');
            $table->timestamp('closed_at')->nullable()->after('counted_cash');
        });
    }

    public function down(): void
    {
        Schema::table('cash_drawers', function (Blueprint $table) {
            $table->dropColumn(['counted_cash', 'closed_at']);
        });
    }
};
