<?php

use Database\Seeders\NewsGeopoliticalTablesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedGeopoliticalDataTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // Now call the NewsGeopoliticalTablesSeeder
    Artisan::call('db:seed', ['--class' => NewsGeopoliticalTablesSeeder::class]);
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
