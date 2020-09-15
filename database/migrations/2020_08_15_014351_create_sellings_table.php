<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->references('id')->on('customers')->onDelete('cascade');
            $table->string('number_transaction');
            $table->date('transaction_date');
            $table->double('total_price');
            $table->double('total_qty');
            $table->double('total_profit');
            $table->double('money');
            $table->double('refund');
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
        Schema::dropIfExists('sellings');
    }
}
