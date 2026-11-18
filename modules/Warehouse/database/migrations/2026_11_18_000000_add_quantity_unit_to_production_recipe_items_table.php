<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_recipe_items', function (Blueprint $table) {
            $table->string('quantity_unit')->nullable()->after('quantity_required');
        });
    }

    public function down(): void
    {
        Schema::table('production_recipe_items', function (Blueprint $table) {
            $table->dropColumn('quantity_unit');
        });
    }
};
