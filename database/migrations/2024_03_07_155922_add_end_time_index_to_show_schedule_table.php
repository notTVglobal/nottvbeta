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
      Schema::table('show_schedule', function (Blueprint $table) {
        // Add an index to the 'end_dateTime' column
        $table->index('end_time', 'show_schedule_end_time_index');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('show_schedule', function (Blueprint $table) {
        // Remove the index from the 'end_dateTime' column
        $table->dropIndex('show_schedule_end_time_index');
      });
    }
};
