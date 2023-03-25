<?php

namespace Database\Seeders;

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
            TeamSeeder::class,
        ]);
    }
}
