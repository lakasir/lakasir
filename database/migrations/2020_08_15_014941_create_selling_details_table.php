<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selling_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selling_id')->references('id')->on('sellings')->onDelete('cascade');
            $table->foreignId('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->double('qty');
            $table->double('price');
            $table->double('profit');
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
        Schema::dropIfExists('selling_details');
    }
}
