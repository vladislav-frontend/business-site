<?php

namespace Database\Seeders;

use App\Models\Home;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $home = Home::query()->create([
            'created_at' => date('Y-m-d h:m:s'),
            'updated_at' => date('Y-m-d h:m:s'),
        ]);

        foreach (Language::all() as $language) {
            DB::table('home_translations')->insert([
                'language_id'   => $language->id,
                'home_id'       => $home->id,
                'title'         => '',
                'description'   => '',
                'summary'       => '',
                'created_at'    => date('Y-m-d h:m:s'),
                'updated_at'    => date('Y-m-d h:m:s'),
            ]);
        }
    }
}
