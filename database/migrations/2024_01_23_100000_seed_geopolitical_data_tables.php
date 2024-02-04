<?php

use Database\Seeders\NewsCitiesAndTownsCANSeeder;
use Database\Seeders\NewsCountriesTableSeeder;
use Database\Seeders\NewsFederalElectoralDistrictsCANSeeder;
use Database\Seeders\NewsGeopoliticalTablesSeeder;
use Database\Seeders\NewsPostalCodeCANSeeder;
use Database\Seeders\NewsProvincesTableCANSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // Now call the NewsGeopoliticalTablesSeeder
//    Artisan::call('db:seed', ['--class' => NewsGeopoliticalTablesSeeder::class]);

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

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    //
  }

};
