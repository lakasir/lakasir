<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

/**
 * The exact shape the old count-based generator left behind: ids 5 and 6 both
 * hold SELL0005, and id 7 already holds SELL0006.
 */
test('the code repair survives a candidate another row already holds', function () {
    Schema::table('sellings', function ($table) {
        $table->dropUnique(['code']);
        $table->string('code')->nullable()->change();
    });

    $rows = [];
    foreach (range(1, 4) as $id) {
        $rows[] = ['code' => 'SELL'.str_pad((string) $id, 4, '0', STR_PAD_LEFT)];
    }
    // 5 and 6 collide; 7 already took SELL0006.
    $rows[] = ['code' => 'SELL0005'];
    $rows[] = ['code' => 'SELL0005'];
    $rows[] = ['code' => 'SELL0006'];
    // No truncate: receivables reference sellings, and the tenant DB has no
    // rows here anyway.
    DB::table('sellings')->delete();

    foreach ($rows as $row) {
        DB::table('sellings')->insert($row + [
            'date' => now()->toDateString(),
            'payed_money' => 1000,
            'money_changes' => 0,
            'total_price' => 1000,
            'total_qty' => 1,
            'is_paid' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Run the repair's up() exactly as a tenant migration would.
    $migration = require database_path('migrations/tenant/2026_11_21_000004_add_unique_code_to_sellings_table.php');
    $migration->up();

    $codes = DB::table('sellings')->orderBy('id')->pluck('code')->all();

    expect($codes)->toHaveCount(7)
        ->and(array_unique($codes))->toBe($codes)          // no duplicates
        ->and($codes[4])->toBe('SELL0005')                   // first of the group keeps its code
        ->and($codes[6])->toBe('SELL0006');                  // the row that already held it is untouched

    // The unique index is really there.
    $indexes = collect(DB::select('SHOW INDEX FROM `sellings`'))->pluck('Key_name')->unique();
    expect($indexes)->toContain('sellings_code_unique');
});
