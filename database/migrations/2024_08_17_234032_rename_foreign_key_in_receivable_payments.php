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
        
        Schema::table('receivable_payments', function (Blueprint $table) use ($driver) {
            if ($driver === 'mysql') {
                // For MySQL, use original constraint names (which remain after table/column rename)
                try {
                    $table->dropForeign('debt_payments_debt_id_foreign');
                } catch (Exception $e) {
                    // Fallback to column-based
                    $table->dropForeign(['receivable_id']);
                }
                
                try {
                    $table->dropForeign('debt_payments_payment_method_id_foreign');
                } catch (Exception $e) {
                    $table->dropForeign(['payment_method_id']);
                }
                
                try {
                    $table->dropForeign('debt_payments_user_id_foreign');
                } catch (Exception $e) {
                    $table->dropForeign(['user_id']);
                }
            } else {
                // For SQLite and other databases, use column-based dropping
                $table->dropForeign(['receivable_id']);
                $table->dropForeign(['payment_method_id']);
                $table->dropForeign(['user_id']);
            }
            
            // Add new foreign keys
            $table->foreign(['receivable_id'])->references('id')->on('receivables');
            $table->foreign(['payment_method_id'])->references('id')->on('payment_methods');
            $table->foreign(['user_id'])->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->dropForeign(['receivable_id']);
            $table->dropForeign(['payment_method_id']);
            $table->dropForeign(['user_id']);
            
            $table->foreign('receivable_id', 'debt_payments_debt_id_foreign')->references('id')->on('receivables');
            $table->foreign('payment_method_id', 'debt_payments_payment_method_id_foreign')->references('id')->on('payment_methods');
            $table->foreign('user_id', 'debt_payments_user_id_foreign')->references('id')->on('users');
        });
    }
};
