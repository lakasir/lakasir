<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('modelable');
            $table->foreignId('user_id')->references('id')->on('users')->nullable();
            $table->ipAddress('ip');
            $table->string('info');
            $table->string('url');
            $table->string('referer');
            $table->json('request')->nullable();
            $table->string('devices');
            $table->json('property')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('activity_log');
    }
}
