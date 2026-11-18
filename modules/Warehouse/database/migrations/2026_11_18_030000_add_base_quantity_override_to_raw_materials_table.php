<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A package ("1 pack") has no fixed size: a pack of saus is 24 pcs, a
     * pack of paper is 100. Let the material declare how many base units its
     * purchase unpacked into, which is exactly the `base_qty` column of a
     * recipe sheet.
     */
    public function up(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            // Null means "derive from input_qty * unit factor".
            $table->double('base_quantity_override')->nullable()->after('base_qty');
        });
    }

    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropColumn('base_quantity_override');
        });
    }
};
