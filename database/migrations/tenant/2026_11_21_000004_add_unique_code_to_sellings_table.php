<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The code is generated from the row id after the insert, so the
        // column has to accept null for that first write.
        Schema::table('sellings', function (Blueprint $table) {
            $table->string('code')->nullable()->change();
        });

        // Codes are generated from the row id after insert now, but older rows
        // may share one (the old counter was a row count). Renumber every row
        // in a duplicated group from its id, then make the column unique.
        $duplicateCodes = DB::table('sellings')
            ->select('code')
            ->groupBy('code')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('code');

        foreach ($duplicateCodes as $code) {
            DB::table('sellings')
                ->where('code', $code)
                ->orderBy('id')
                ->pluck('id')
                ->each(function (int $id) use ($code) {
                    // The first row of the group keeps its printed code.
                    if ($id === DB::table('sellings')->where('code', $code)->min('id')) {
                        return;
                    }
                    DB::table('sellings')
                        ->where('id', $id)
                        ->update(['code' => 'SELL'.str_pad((string) $id, 4, '0', STR_PAD_LEFT)]);
                });
        }

        Schema::table('sellings', function (Blueprint $table) {
            $table->unique('code');
        });
    }

    public function down(): void
    {
        Schema::table('sellings', function (Blueprint $table) {
            $table->dropUnique(['code']);
        });
    }
};
