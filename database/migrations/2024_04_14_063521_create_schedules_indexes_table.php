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
      Schema::create('schedules_indexes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('team_id')->nullable(); // Define the column
        $table->morphs('content'); // This will create content_id and content_type with indexes
        $table->foreignId('schedule_id'); // Define the column
        $table->dateTime('next_broadcast')->nullable()->index();
        $table->timestamps();

        // Set foreign key constraints separately to specify onDelete behavior
        $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');

        // Adding indexes to foreign keys, ensuring they're declared after foreign key constraints
        $table->index('team_id');
        $table->index('schedule_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules_indexes');
    }
};
