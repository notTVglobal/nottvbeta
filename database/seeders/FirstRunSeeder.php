<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FirstRunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            AppSettingsSeeder::class,
            ImageSeeder::class,
            MovieCategorySeeder::class,
            ShowCategorySeeder::class,
        ]);

        Team::create([
            'name' => 'notTV Founders',
            'description' => 'The founding team working actively on the notTV project.',
            'user_id' => '1',
            'slug' => 'nottv-founders',
            'image_id' => '3',
        ]);

        Team::create([
            'name' => 'notTV News',
            'description' => 'The notTV News Team.',
            'user_id' => '1',
            'slug' => 'nottv-news',
            'image_id' => '3',
        ]);
    }
}
