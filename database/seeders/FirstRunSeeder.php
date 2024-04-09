<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FirstRunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Seeders for initial, foundational data
        $this->call([
            AdminSeeder::class,
            AppSettingsSeeder::class,
            ImageSeeder::class,
            MovieCategorySeeder::class,
            MovieCategorySubsTableSeeder::class,
            ShowCategorySeeder::class,
            ShowCategorySubsTableSeeder::class,
            NewsCategorySeeder::class,
            NewsCategorySubsTableSeeder::class,
            NewsCountriesTableSeeder::class,
            NewsProvincesTableCANSeeder::class,
            NewsCitiesAndTownsCANSeeder::class,
            NewsPostalCodeCANSeeder::class,
            NewsFederalElectoralDistrictsCANSeeder::class,
            NewsFederalElectoralDistrictsMoreInfoCANSeeder::class,
        ]);

        // Set default country_id
        DB::table('app_settings')->update(['country_id' => 4]);

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
