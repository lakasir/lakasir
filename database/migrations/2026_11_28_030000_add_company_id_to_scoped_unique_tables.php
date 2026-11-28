<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * settings, members and barcodes declare company_id in their create migration
 * (their unique keys need it), but an install that ran the pre-multi-company
 * version of those files already has the table and never gets the column: the
 * create is skipped, and 2026_11_25_000002 deliberately leaves these three out.
 * The result is `Unknown column 'settings.company_id'` on every read.
 *
 * Idempotent, because a fresh install already has the column.
 */
return new class extends Migration
{
    public function up(): void
    {
        $companyId = DB::table('companies')->orderBy('id')->value('id');

        $this->addCompanyId('settings');
        $this->addCompanyId('members');
        $this->addCompanyId('barcodes');

        if ($companyId === null) {
            return;
        }

        foreach (['settings', 'members', 'barcodes'] as $table) {
            DB::table($table)->whereNull('company_id')->update(['company_id' => $companyId]);
        }

        $this->scopeUnique('settings', ['company_id', 'key']);
        $this->scopeUnique('members', ['company_id', 'code']);
        $this->scopeUnique('members', ['company_id', 'email']);
        $this->scopeUnique('barcodes', ['company_id', 'code']);
    }

    private function addCompanyId(string $table): void
    {
        if (Schema::hasColumn($table, 'company_id')) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) {
            $blueprint->foreignId('company_id')->nullable()->index();
        });
    }

    /**
     * One key per shop, not per install: the old single-column index made the
     * first shop to use a setting key, a member code or a barcode own it for
     * every other. Rebuilt under the same name a fresh install would use.
     *
     * @param  array<int, string>  $columns
     */
    private function scopeUnique(string $table, array $columns): void
    {
        $uniques = collect(DB::select("show index from `{$table}`"))
            ->filter(fn (object $i): bool => ! $i->Non_unique && $i->Key_name !== 'PRIMARY')
            ->groupBy('Key_name')
            ->map(fn ($rows): array => [
                'name' => $rows->first()->Key_name,
                'columns' => $rows->pluck('Column_name')->all(),
            ]);

        if ($uniques->contains(fn (array $index): bool => $index['columns'] === $columns)) {
            return;
        }

        $stale = $uniques->filter(fn (array $index): bool => count($index['columns']) < count($columns)
            && array_diff($index['columns'], $columns) === []);

        foreach ($stale as $index) {
            Schema::table($table, function (Blueprint $blueprint) use ($index) {
                $blueprint->dropUnique($index['name']);
            });
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns) {
            $blueprint->unique($columns);
        });
    }

    public function down(): void
    {
        // One-way: rows a shop really owns cannot be told apart from orphans.
    }
};
