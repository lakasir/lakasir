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
        if (! Schema::hasTable('abouts')) {
            Schema::create('abouts', function (Blueprint $table) {
                $table->id();
                $table->string('shop_name')->nullable();
                $table->string('shop_location')->nullable();
                $table->string('currency')->default('IDR');
                $table->string('business_type')->nullable();
                $table->string('photo')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('abouts', function (Blueprint $table) {
                if (Schema::hasColumn('abouts', 'tenant_user_id')) {
                    $table->dropColumn('tenant_user_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
