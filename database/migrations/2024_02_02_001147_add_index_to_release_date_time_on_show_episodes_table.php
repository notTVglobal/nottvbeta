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
    Schema::table('show_episodes', function (Blueprint $table) {
      // Add an index to the 'releaseDateTime' column
      $table->index('release_dateTime', 'show_episodes_release_date_time_index');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('show_episodes', function (Blueprint $table) {
      // Drop the index if the migration is rolled back
      $table->dropIndex('show_episodes_release_date_time_index');
    });
  }
};
