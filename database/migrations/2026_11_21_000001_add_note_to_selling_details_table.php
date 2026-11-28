<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('selling_details', function (Blueprint $table) {
            $table->string('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('selling_details', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};
