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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->after('phone_number', function (Blueprint $table) {
                $table->string('contact_name')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->nullable();
                $table->string('postal_code')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('contact_name');
            $table->dropColumn('email');
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('country');
            $table->dropColumn('postal_code');
        });
    }
};
