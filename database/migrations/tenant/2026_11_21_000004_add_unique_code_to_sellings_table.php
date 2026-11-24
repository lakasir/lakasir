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
        // may share one (the old counter was a row count). Renumber the
        // duplicates, keeping the first row of each group's printed code, and
        // skip any candidate another row already holds: the id-derived code can
        // land on a code that exists (ids 5 and 6 both SELL0005 while id 7 has
        // SELL0006), and a collision would make the unique index below fail and
        // leave the tenant half-migrated.
        $codesById = DB::table('sellings')->whereNotNull('code')->pluck('code', 'id')->all();

        $duplicateCodes = DB::table('sellings')
            ->select('code')
            ->whereNotNull('code')
            ->groupBy('code')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('code');

        foreach ($duplicateCodes as $code) {
            $ids = DB::table('sellings')->where('code', $code)->orderBy('id')->pluck('id')->all();

            foreach ($ids as $index => $id) {
                if ($index === 0) {
                    // The first row keeps the code already printed on a receipt.
                    continue;
                }

                $base = 'SELL'.str_pad((string) $id, 4, '0', STR_PAD_LEFT);
                $candidate = $base;
                $suffix = 1;

                while ($this->heldByAnotherRow($codesById, $candidate, $id)) {
                    $candidate = $base.'-'.$suffix;
                    $suffix++;
                }

                $codesById[$id] = $candidate;

                DB::table('sellings')->where('id', $id)->update(['code' => $candidate]);
            }
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

    /**
     * @param  array<int, string>  $codesById
     */
    private function heldByAnotherRow(array $codesById, string $candidate, int $exceptId): bool
    {
        foreach ($codesById as $id => $existing) {
            if ($id !== $exceptId && $existing === $candidate) {
                return true;
            }
        }

        return false;
    }
};
