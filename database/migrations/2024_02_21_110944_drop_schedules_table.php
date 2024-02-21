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
      Schema::dropIfExists('schedules');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      // Code to recreate the 'schedules' table if needed
      Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->longtext('description');
        $table->datetime('datetime_start');
        $table->datetime('datetime_end');
        $table->timestamps();
      });
    }
};
