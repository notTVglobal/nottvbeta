<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
    Schema::table('movies', function (Blueprint $table) {
      // Add the releaseDateTime column as a datetime type
      $table->dateTime('releaseDateTime')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('movies', function (Blueprint $table) {
      // Remove the releaseDateTime column if the migration is rolled back
      $table->dropColumn('releaseDateTime');
    });
  }
};
