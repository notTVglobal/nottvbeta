<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    // Drop the foreign key before renaming the table
    Schema::table('show_schedule', function (Blueprint $table) {
      // Drop foreign key based on the exact name from the database
      $table->dropForeign('schedules_recurrence_details_id_foreign');
    });
    // Rename the table
    Schema::rename('show_schedule', 'schedules');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */

  public function down() {
    // Rename the table back to its original name
    Schema::rename('schedules', 'show_schedule');
//
//    // Recreate the foreign key and indexes in the original 'show_schedule' table
    Schema::table('show_schedule', function (Blueprint $table) {
//      // Reapply the foreign key and indexes to match the original schema setup
      $table->foreign('recurrence_details_id', 'schedules_recurrence_details_id_foreign')
          ->references('id')->on('show_schedule_recurrence_details')
          ->onDelete('set null');
    });
  }

};
