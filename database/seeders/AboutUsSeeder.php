<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $about_us = AboutUs::query()->create([
            'created_at'    => date('Y-m-d h:m:s'),
            'updated_at'    => date('Y-m-d h:m:s'),
        ]);

        foreach (Language::all() as $language) {
            DB::table('about_us_translations')->insert([
                'language_id' => $language->id,
                'aboutus_id' => $about_us->id,
                'title' => '',
                'description' => '',
                'summary' => '',
                'created_at' => date('Y-m-d h:m:s'),
                'updated_at' => date('Y-m-d h:m:s'),
            ]);
        }
    }
}
