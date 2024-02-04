<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsGeopoliticalTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $this->call([
          NewsCountriesTableSeeder::class,
        // Change these seeders as needed:
          NewsProvincesTableCANSeeder::class,
          NewsFederalElectoralDistrictsCANSeeder::class,
//        SubnationalElectoralDistrictsTableSeeder::class,  // we don't have this file yet (tec21: 2024-02-03)
          NewsCitiesAndTownsCANSeeder::class,
          NewsPostalCodeCANSeeder::class,
      ]);
    }
}
