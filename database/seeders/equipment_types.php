<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class equipment_types extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('equipment_types')->insert([
            'type_name' => 'TP-Link TL-WR74',
            'sn_mask' => 'XXAAAAAXAA'
        ]);
        DB::table('equipment_types')->insert([
            'type_name' => 'D-Link DIR-300',
            'sn_mask' => 'NXXAAXZXaa'
        ]);
        DB::table('equipment_types')->insert([
            'type_name' => 'D-Link DIR-300 S',
            'sn_mask' => 'NXXAAXZXXX'
        ]);
    }
}
