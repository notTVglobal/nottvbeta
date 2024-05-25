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
      Schema::table('schedules', function (Blueprint $table) {
        // Drop the old indexes
        $table->dropIndex('show_schedule_content_type_content_id_index');
        $table->dropIndex('show_schedule_end_time_index');
        $table->dropIndex('show_schedule_priority_index');
        $table->dropIndex('show_schedule_recurrence_flag_index');
        $table->dropIndex('show_schedule_start_time_index');
        $table->dropIndex('show_schedule_status_index');
        $table->dropIndex('show_schedule_type_index');

        // Create new indexes with the correct naming
        $table->index(['content_type', 'content_id'], 'schedules_content_type_content_id_index');
        $table->index('end_time', 'schedules_end_time_index');
        $table->index('priority', 'schedules_priority_index');
        $table->index('recurrence_flag', 'schedules_recurrence_flag_index');
        $table->index('start_time', 'schedules_start_time_index');
        $table->index('status', 'schedules_status_index');
        $table->index('type', 'schedules_type_index');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('schedules', function (Blueprint $table) {
        // Reverse the changes: drop new indexes and re-add old ones
        $table->dropIndex('schedules_content_type_content_id_index');
        $table->dropIndex('schedules_end_dateTime_index');
        $table->dropIndex('schedules_priority_index');
        $table->dropIndex('schedules_recurrence_flag_index');
        $table->dropIndex('schedules_start_time_index');
        $table->dropIndex('schedules_status_index');
        $table->dropIndex('schedules_type_index');

        // Recreate the original indexes with old naming
        $table->index(['content_type', 'content_id'], 'show_schedule_content_type_content_id_index');
        $table->index('end_time', 'show_schedule_end_time_index');
        $table->index('priority', 'show_schedule_priority_index');
        $table->index('recurrence_flag', 'show_schedule_recurrence_flag_index');
        $table->index('start_time', 'show_schedule_start_time_index');
        $table->index('status', 'show_schedule_status_index');
        $table->index('type', 'show_schedule_type_index');
      });
    }
};
