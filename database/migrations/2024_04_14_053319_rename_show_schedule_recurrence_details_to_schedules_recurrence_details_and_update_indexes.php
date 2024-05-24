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
//      Schema::table('show_schedule_recurrence_details', function (Blueprint $table) {
        // Drop the existing indexes
//        $table->dropIndex('show_schedule_recurrence_details_friday_index');
//        $table->dropIndex('show_schedule_recurrence_details_monday_index');
//        $table->dropIndex('show_schedule_recurrence_details_saturday_index');
//        $table->dropIndex('show_schedule_recurrence_details_start_dateTime_index');
//        $table->dropIndex('show_schedule_recurrence_details_sunday_index');
//        $table->dropIndex('show_schedule_recurrence_details_thursday_index');
//        $table->dropIndex('show_schedule_recurrence_details_timezone_index');
//        $table->dropIndex('show_schedule_recurrence_details_tuesday_index');
//        $table->dropIndex('show_schedule_recurrence_details_wednesday_index');
//      });
//
//      // Rename the table
      Schema::rename('show_schedule_recurrence_details', 'schedule_recurrence_details');

//      Schema::table('schedule_recurrence_details', function (Blueprint $table) {
//        // Recreate the indexes with new naming
//        $table->index('friday', 'schedules_recurrence_details_friday_index');
//        $table->index('monday', 'schedules_recurrence_details_monday_index');
//        $table->index('saturday', 'schedules_recurrence_details_saturday_index');
//        $table->index('start_dateTime', 'schedules_recurrence_details_start_dateTime_index');
//        $table->index('sunday', 'schedules_recurrence_details_sunday_index');
//        $table->index('thursday', 'schedules_recurrence_details_thursday_index');
//        $table->index('timezone', 'schedules_recurrence_details_timezone_index');
//        $table->index('tuesday', 'schedules_recurrence_details_tuesday_index');
//        $table->index('wednesday', 'schedules_recurrence_details_wednesday_index');
//      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
  public function down()
  {
    // First, rename the table back to the original name
    Schema::rename('schedule_recurrence_details', 'show_schedule_recurrence_details');

    // After renaming, modify the table
//    Schema::table('show_schedule_recurrence_details', function (Blueprint $table) {
      // Drop the new indexes that were added to the renamed table
//      $table->dropIndex('schedules_recurrence_details_friday_index');
//      $table->dropIndex('schedules_recurrence_details_monday_index');
//      $table->dropIndex('schedules_recurrence_details_saturday_index');
//      $table->dropIndex('schedules_recurrence_details_start_dateTime_index');
//      $table->dropIndex('schedules_recurrence_details_sunday_index');
//      $table->dropIndex('schedules_recurrence_details_thursday_index');
//      $table->dropIndex('schedules_recurrence_details_timezone_index');
//      $table->dropIndex('schedules_recurrence_details_tuesday_index');
//      $table->dropIndex('schedules_recurrence_details_wednesday_index');

      // Assuming you need to recreate any indexes that were originally on the table
//      $table->index('friday', 'show_schedule_recurrence_details_friday_index');
//      $table->index('monday', 'show_schedule_recurrence_details_monday_index');
//      $table->index('saturday', 'show_schedule_recurrence_details_saturday_index');
//      $table->index('start_dateTime', 'show_schedule_recurrence_details_start_dateTime_index');
//      $table->index('sunday', 'show_schedule_recurrence_details_sunday_index');
//      $table->index('thursday', 'show_schedule_recurrence_details_thursday_index');
//      $table->index('timezone', 'show_schedule_recurrence_details_timezone_index');
//      $table->index('tuesday', 'show_schedule_recurrence_details_tuesday_index');
//      $table->index('wednesday', 'show_schedule_recurrence_details_wednesday_index');
//    });
  }

};
