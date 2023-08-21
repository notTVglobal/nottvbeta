<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Array_;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        Team::create([
//            'name' => 'notTV Founders',
//            'description' => 'The founding team working actively on the notTV project.',
//            'user_id' => '1',
//            'slug' => 'nottv-founders',
//            'image_id' => '3',
//        ]);
//
//        Team::create([
//            'name' => 'notTV News',
//            'description' => 'The notTV News Team.',
//            'user_id' => '1',
//            'slug' => 'nottv-news',
//            'image_id' => '3',
//        ]);

        \App\Models\Team::factory(5)->create();
    }
}
