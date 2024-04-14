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
      Schema::table('schedule_recurrence_details', function (Blueprint $table) {
        $table->dropIndex('show_schedule_recurrence_details_friday_index');
        $table->dropIndex('show_schedule_recurrence_details_monday_index');
        $table->dropIndex('show_schedule_recurrence_details_saturday_index');
        $table->dropIndex('show_schedule_recurrence_details_start_time_index');
        $table->dropIndex('show_schedule_recurrence_details_sunday_index');
        $table->dropIndex('show_schedule_recurrence_details_thursday_index');
        $table->dropIndex('show_schedule_recurrence_details_timezone_index');
        $table->dropIndex('show_schedule_recurrence_details_tuesday_index');
        $table->dropIndex('show_schedule_recurrence_details_wednesday_index');
        // Recreate the indexes with new naming
        $table->index('friday', 'schedule_recurrence_details_friday_index');
        $table->index('monday', 'schedule_recurrence_details_monday_index');
        $table->index('saturday', 'schedule_recurrence_details_saturday_index');
        $table->index('start_time', 'schedule_recurrence_details_start_time_index');
        $table->index('sunday', 'schedule_recurrence_details_sunday_index');
        $table->index('thursday', 'schedule_recurrence_details_thursday_index');
        $table->index('timezone', 'schedule_recurrence_details_timezone_index');
        $table->index('tuesday', 'schedule_recurrence_details_tuesday_index');
        $table->index('wednesday', 'schedule_recurrence_details_wednesday_index');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    Schema::table('show_schedule_recurrence_details', function (Blueprint $table) {
//       Drop the new indexes that were added to the renamed table
//      $table->dropIndex('schedule_recurrence_details_friday_index');
//      $table->dropIndex('schedule_recurrence_details_monday_index');
//      $table->dropIndex('schedule_recurrence_details_saturday_index');
//      $table->dropIndex('schedule_recurrence_details_start_time_index');
//      $table->dropIndex('schedule_recurrence_details_sunday_index');
//      $table->dropIndex('schedule_recurrence_details_thursday_index');
//      $table->dropIndex('schedule_recurrence_details_timezone_index');
//      $table->dropIndex('schedule_recurrence_details_tuesday_index');
//      $table->dropIndex('schedule_recurrence_details_wednesday_index');

//       Assuming you need to recreate any indexes that were originally on the table
//      $table->index('friday', 'show_schedule_recurrence_details_friday_index');
//      $table->index('monday', 'show_schedule_recurrence_details_monday_index');
//      $table->index('saturday', 'show_schedule_recurrence_details_saturday_index');
//      $table->index('start_time', 'show_schedule_recurrence_details_start_time_index');
//      $table->index('sunday', 'show_schedule_recurrence_details_sunday_index');
//      $table->index('thursday', 'show_schedule_recurrence_details_thursday_index');
//      $table->index('timezone', 'show_schedule_recurrence_details_timezone_index');
//      $table->index('tuesday', 'show_schedule_recurrence_details_tuesday_index');
//      $table->index('wednesday', 'show_schedule_recurrence_details_wednesday_index');
    });
    }
};
