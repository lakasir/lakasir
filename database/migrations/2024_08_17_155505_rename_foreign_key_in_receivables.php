<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        Schema::table('receivables', function (Blueprint $table) use ($driver) {
            if ($driver === 'mysql') {
                // For MySQL, use constraint names (which remain after table rename)
                try {
                    $table->dropForeign('debts_member_id_foreign');
                } catch (Exception $e) {
                    // Try alternative constraint name or column-based
                    $table->dropForeign(['member_id']);
                }
                
                try {
                    $table->dropForeign('debts_selling_id_foreign');
                } catch (Exception $e) {
                    // Try alternative constraint name or column-based
                    $table->dropForeign(['selling_id']);
                }
            } else {
                // For SQLite and other databases, use column-based dropping
                $table->dropForeign(['member_id']);
                $table->dropForeign(['selling_id']);
            }
            
            // Add new foreign keys
            $table->foreign(['member_id'])->references('id')->on('members')->onDelete('cascade');
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
            $table->dropForeign(['selling_id']);
            
            $table->foreign('member_id', 'debts_member_id_foreign')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('selling_id', 'debts_selling_id_foreign')->references('id')->on('sellings')->onDelete('cascade');
        });
    }
};
