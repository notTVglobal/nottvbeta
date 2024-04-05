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
      Schema::table('show_schedule_recurrence_details', function (Blueprint $table) {
        // Adding an index to the start_time column
        $table->index('start_time');

        // Adding an index to the timezone column
        $table->index('timezone');
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
        // Removing the index from the start_time column
        $table->dropIndex(['start_time']);

        // Removing the index from the timezone column
        $table->dropIndex(['timezone']);
      });
    }
};
