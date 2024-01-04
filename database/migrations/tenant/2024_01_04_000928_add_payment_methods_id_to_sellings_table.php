<?php

use App\Models\Tenants\PaymentMethod;
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
            $table->boolean('friend_price')->default(false)->after('total_price');
            $table->after('friend_price', function (Blueprint $table) {
                $table->foreignIdFor(PaymentMethod::class, 'payment_method_id')
                    ->nullable()
                    ->constrained()
                    ->onDelete('cascade');
                $table->double('tax')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellings', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn('payment_method_id');
            $table->dropColumn('friend_price');
            $table->dropColumn('tax');
        });
    }
};
