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
        Schema::create('qris_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained()->onDelete('cascade');
            $table->string('qris_invoice_id')->nullable();
            $table->text('qris_content')->nullable();
            $table->timestamp('qris_request_date')->nullable();
            $table->string('qris_nmid')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('transaction_number');
            $table->enum('status', ['pending', 'paid', 'expired', 'failed'])->default('pending');
            $table->json('cart_data');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qris_transactions');
    }
};
