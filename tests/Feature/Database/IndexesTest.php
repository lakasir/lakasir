<?php

use Illuminate\Support\Facades\DB;
use Tests\RefreshDatabaseWithTenant;

uses(RefreshDatabaseWithTenant::class);

/**
 * @return array<int, string> every indexed column of a tenant table
 */
function indexedColumns(string $table): array
{
    return collect(DB::select("SHOW INDEX FROM `$table`"))
        ->map(fn ($index) => $index->Column_name)
        ->unique()
        ->values()
        ->all();
}

test('the hot report, stock and search columns are indexed', function () {
    expect(indexedColumns('sellings'))->toContain('date', 'created_at', 'is_paid')
        ->and(indexedColumns('stocks'))->toContain('product_id', 'is_ready', 'type', 'stock')
        ->and(indexedColumns('products'))->toContain('sku', 'show', 'type');
});

test('a report window uses the date index instead of scanning the table', function () {
    $plan = collect(DB::select("EXPLAIN SELECT * FROM sellings WHERE date BETWEEN '2026-01-01' AND '2026-01-31'"))
        ->first();

    // `key` is the index MySQL chose; NULL means a full table scan.
    expect($plan->key)->toBe('sellings_date_index');
});
