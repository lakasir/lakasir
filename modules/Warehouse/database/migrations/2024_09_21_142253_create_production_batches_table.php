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
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('stock_id')->nullable()->constrained();
            $table->integer('quantity_produced');
            $table->double('total_cost')->default(0);
            $table->double('added_cost')->default(0);
            $table->double('margin')->default(0);
            $table->boolean('complete_price')->default(false);
            $table->enum('status', ['prepared', 'started', 'finished'])->default('prepared');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
