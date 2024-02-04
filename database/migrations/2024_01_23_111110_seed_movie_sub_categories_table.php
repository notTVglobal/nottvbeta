<?php

use Database\Seeders\MovieCategorySubsTableSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedMovieCategorySubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Now call the seeder
      Artisan::call('db:seed', [
          '--class' => MovieCategorySubsTableSeeder::class
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
