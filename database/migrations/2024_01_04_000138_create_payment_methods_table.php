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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_cash')->default(true);
            $table->boolean('is_debit')->default(false);
            $table->boolean('is_credit')->default(false);
            $table->boolean('is_wallet')->default(false);
            $table->string('icon')->nullable();
            $table->nullableMorphs('waletable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
