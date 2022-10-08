<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('teams')->insert([
        'name' => 'notTV Founders',
        'description' => 'The founding team working actively on the notTV project.',
        'user_id' => '1',
        'slug' => 'nottv-founders',
        'image_id' => '3',
    ]);

        DB::table('teams')->insert([
            'name' => 'notTV News',
            'description' => 'The notTV News Team.',
            'user_id' => '1',
            'slug' => 'nottv-news',
            'image_id' => '3',
        ]);

        \App\Models\Team::factory(99)->create();
    }
}
