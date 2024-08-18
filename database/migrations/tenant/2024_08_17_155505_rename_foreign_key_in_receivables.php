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
            $table->dropForeign('debts_member_id_foreign');
            $table->foreign(['member_id'])->references('id')->on('members')->onDelete('cascade');

            $table->dropForeign('debts_selling_id_foreign');
            $table->foreign(['selling_id'])->references('id')->on('sellings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receivables', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->foreign('member_id', 'debts_member_id_foreign')->references('id')->on('members')->onDelete('cascade');

            $table->dropForeign(['selling_id']);
            $table->foreign('selling_id', 'debts_selling_id_foreign')->references('id')->on('sellings')->onDelete('cascade');
        });
    }
};
