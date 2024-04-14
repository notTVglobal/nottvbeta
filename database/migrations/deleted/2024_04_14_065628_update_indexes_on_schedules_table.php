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
      // Recreate foreign keys and indexes with new naming after renaming
      Schema::table('schedules', function (Blueprint $table) {
        $table->index(['content_type', 'content_id'], 'schedules_content_type_content_id_index');
        $table->index(['end_time'], 'schedules_end_time_index');
        $table->index(['priority'], 'schedules_priority_index');
        $table->index(['recurrence_flag'], 'schedules_recurrence_flag_index');
        $table->index(['start_time'], 'schedules_start_time_index');
        $table->index(['status'], 'schedules_status_index');
        $table->index(['type'], 'schedules_type_index');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//      // Reverse the process for rollback
      Schema::table('schedules', function (Blueprint $table) {
        // Drop recreated indexes
        $table->dropIndex('schedules_content_type_content_id_index');
        $table->dropIndex('schedules_end_time_index');
        $table->dropIndex('schedules_priority_index');
        $table->dropIndex('schedules_recurrence_flag_index');
        $table->dropIndex('schedules_start_time_index');
        $table->dropIndex('schedules_status_index');
        $table->dropIndex('schedules_type_index');
      });
    }
};
