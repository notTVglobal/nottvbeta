<?php

namespace Database\Seeders;

use App\Models\NewsCountry;
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
        // Find the country with the name 'Canada'
        $country = NewsCountry::where('name', 'Canada')->first();

        if ($country) {
            // If the country exists, proceed with the update
            DB::table('app_settings')->update(['country_id' => $country->id]);
        } else {
            // Handle the case where the country does not exist
            // You might want to log an error or create the country entry
            $this->command->warn("Country with the name 'Canada' does not exist in news_countries table.");
        }

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
