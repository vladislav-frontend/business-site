<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Contacts;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contacts = Contacts::query()->create([
            'phone_1'       => '',
            'phone_2'       => '',
            'telegram'      => '',
            'email'         => '',
            'skype'         => '',
            'created_at'    => date('Y-m-d h:m:s'),
            'updated_at'    => date('Y-m-d h:m:s'),
        ]);

        foreach (Language::all() as $language) {
            DB::table('contacts_translations')->insert([
                'language_id'   => $language->id,
                'contacts_id'   => $contacts->id,
                'title'         => '',
                'description'   => '',
                'created_at'    => date('Y-m-d h:m:s'),
                'updated_at'    => date('Y-m-d h:m:s'),
            ]);
        }
    }
}
