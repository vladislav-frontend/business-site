<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'geo'       => '',
            'facebook'  => '',
            'instagram' => '',
            'linkedin'  => '',
            'created_at'    => date('Y-m-d h:m:s'),
            'updated_at'    => date('Y-m-d h:m:s'),
        ]);
    }
}
