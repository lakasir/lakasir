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
        Schema::table('members', function (Blueprint $table) {
            $table->after('name', function (Blueprint $table) {
                $table->string('identity_type')->nullable();
                $table->string('identity_number')->nullable();
                $table->dateTime('joined_date')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('identity_type');
            $table->dropColumn('identity_number');
            $table->dropColumn('joined_date');
        });
    }
};
