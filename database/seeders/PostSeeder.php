<?php

namespace Database\Seeders;

use App\Models\Post;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $faker = Faker::create();
//        $date = "$faker->date  $faker->time";
//        $name = $faker->text($maxNbChars = 20);
//
//        $post = Post::query()->create([
//            'user_id'       => 1,
//            'category_id'   => 6,
//            'post_slug'   => $name,
//            'image'         => $faker->imageUrl(640, 480, 'animals', true),
//            'readtime'      => $faker->randomDigit(),
//            'created_at'    => $date,
//            'updated_at'    => $date,
//        ]);
//
//        DB::table('translations')->insert([
//            'language_id'   => 1,
//            'post_id'   => $post->id,
//            'title'         => $name,
//            'description'   => $faker->text($maxNbChars = 200),
//            'name'          => $name,
//            'summary'       => $faker->text($maxNbChars = 500),
//            'content'       => $faker->text($maxNbChars = 2000),
//        ]);
    }
}
