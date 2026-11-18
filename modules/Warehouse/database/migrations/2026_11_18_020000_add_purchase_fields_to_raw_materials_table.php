<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Warehouse\Models\RawMaterial;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            // What was purchased: e.g. 1 kg for 37,000.
            $table->double('input_qty')->nullable()->after('sku');
            $table->string('input_unit')->nullable()->after('input_qty');
            $table->double('harga_total')->nullable()->after('input_unit');

            // That purchase expressed in small units, and the cost of one.
            $table->double('base_qty')->nullable()->after('harga_total');
            $table->string('base_unit')->nullable()->after('base_qty');
            $table->double('price_per_base_unit')->nullable()->after('base_unit');
        });

        $this->backfill();

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->double('quantity')->change();
        });
    }

    /**
     * Existing rows only had `unit` + `price_per_unit`, where the price was
     * meant per whole unit. Treat that as one base unit purchased.
     */
    private function backfill(): void
    {
        foreach (DB::table('raw_materials')->get() as $row) {
            $baseUnit = RawMaterial::normalizeUnit($row->unit);

            DB::table('raw_materials')->where('id', $row->id)->update([
                'input_qty' => 1,
                'input_unit' => $row->unit,
                'harga_total' => $row->price_per_unit,
                'base_qty' => RawMaterial::unitFactor($baseUnit) ?? 1,
                'base_unit' => $baseUnit,
                'price_per_base_unit' => $row->price_per_unit / (RawMaterial::unitFactor($baseUnit) ?? 1),
                'unit' => $baseUnit,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropColumn([
                'input_qty', 'input_unit', 'harga_total',
                'base_qty', 'base_unit', 'price_per_base_unit',
            ]);
        });

        Schema::table('raw_materials', function (Blueprint $table) {
            $table->integer('quantity')->change();
        });
    }
};
