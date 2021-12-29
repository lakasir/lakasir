<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('unit')->nullable();
            /** 0. Default 1. IMEI 2. VARIAN 3. Multi Satuan 4. Paket 5. Bahan Baku */
            $table->enum('item_type', [0, 1, 2, 3, 4, 5])->comment("0. Default 1. IMEI 2. VARIAN 3. Multi Satuan 4. Paket 5. Bahan Baku")->default(0);
            $table->string('sku')->nullable()->unique();
            $table->boolean('internal_production')->default(0);
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
