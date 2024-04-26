<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('tenants:run app:fill-is-owner-to-users');
        // Schema::dropIfExists('tenant_users');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::create('tenant_users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('tenant_id')->index();
        //     $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        //     $table->string('full_name');
        //     $table->string('email')->unique();
        //     $table->string('password')->nullable();
        //     $table->timestamps();
        // });
    }
};
