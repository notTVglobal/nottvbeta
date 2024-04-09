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
        // Change start_date and end_date to datetime
        $table->dateTime('start_date')->change();
        $table->dateTime('end_date')->change();
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
          // Revert back to date
          $table->date('start_date')->change();
          $table->date('end_date')->change();
        });
    }
};
