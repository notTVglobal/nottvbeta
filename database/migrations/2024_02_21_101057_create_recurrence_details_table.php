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
      Schema::create('recurrence_details', function (Blueprint $table) {
        $table->id();
        $table->string('frequency')->comment('Frequency of recurrence (e.g., daily, weekly, monthly)');
        $table->json('days_of_week')->nullable()->comment('JSON array of days for weekly recurrences. e.g., ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"] for weekdays');
        $table->time('start_time')->comment('Start time of the recurrence');
        $table->integer('duration_minutes')->comment('Duration of the recurrence in minutes');
        $table->date('start_date')->nullable()->comment('Optional start date for the recurrence period');
        $table->date('end_date')->nullable()->comment('Optional end date for the recurrence period');
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurrence_details');
    }
};
