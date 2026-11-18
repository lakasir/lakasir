<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('family');
            $table->double('factor');
            $table->timestamps();
        });

        $now = now();

        DB::table('units')->insert([
            ['name' => 'mg', 'family' => 'mass', 'factor' => 0.001, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'g', 'family' => 'mass', 'factor' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'dag', 'family' => 'mass', 'factor' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'hg', 'family' => 'mass', 'factor' => 100, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'kg', 'family' => 'mass', 'factor' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'ml', 'family' => 'volume', 'factor' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'cl', 'family' => 'volume', 'factor' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'dl', 'family' => 'volume', 'factor' => 100, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'l', 'family' => 'volume', 'factor' => 1000, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'pcs', 'family' => 'count', 'factor' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'pack', 'family' => 'count', 'factor' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'tabung', 'family' => 'count', 'factor' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'masakan', 'family' => 'count', 'factor' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
