<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = __DIR__ . '/../factories/timezonedb.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
