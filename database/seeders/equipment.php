<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class equipment extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 50; $i++)
        DB::table('equipments')->insert([
            'equipment_type_id' => rand(1, 3),
            'serial_number' => Str::random(10),
            'comment' => 'Comment ' . $i . ' ____ ' . rand(10000, 99999),
        ]);
    }
}
