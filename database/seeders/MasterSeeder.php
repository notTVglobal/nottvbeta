<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSeeder extends Seeder
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
        DB::table('news_stories')->update(['country_id' => 4]);

        // Set default news_category_id
        DB::table('news_stories')->update(['news_category_id' => 1]);
    }
}
