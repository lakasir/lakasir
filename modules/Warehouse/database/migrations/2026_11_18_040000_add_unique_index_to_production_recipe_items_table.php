<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One recipe line per material per product: two rows for the same
     * material would double its cost in the HPP.
     */
    public function up(): void
    {
        // Collapse any duplicates already present, keeping the oldest row.
        $duplicates = DB::table('production_recipe_items')
            ->select('product_id', 'raw_material_id')
            ->groupBy('product_id', 'raw_material_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $keep = DB::table('production_recipe_items')
                ->where('product_id', $duplicate->product_id)
                ->where('raw_material_id', $duplicate->raw_material_id)
                ->orderBy('id')
                ->value('id');

            DB::table('production_recipe_items')
                ->where('product_id', $duplicate->product_id)
                ->where('raw_material_id', $duplicate->raw_material_id)
                ->where('id', '!=', $keep)
                ->delete();
        }

        Schema::table('production_recipe_items', function (Blueprint $table) {
            $table->unique(['product_id', 'raw_material_id'], 'production_recipe_items_product_material_unique');
        });
    }

    public function down(): void
    {
        Schema::table('production_recipe_items', function (Blueprint $table) {
            $table->dropUnique('production_recipe_items_product_material_unique');
        });
    }
};
