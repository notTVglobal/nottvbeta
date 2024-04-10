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
      Schema::table('channel_playlists', function (Blueprint $table) {
        // Specifies the type of playlist (e.g., Regular, Event, Special)
        // This helps in categorizing playlists based on their purpose or content.
        $table->string('type')->after('url')->default('Regular')->comment('The type of playlist, such as Regular, Event, or Special.');

        // Indicates the current status of the playlist (e.g., Active, Inactive, Scheduled)
        // Useful for managing which playlists are currently being used or are scheduled for use.
        $table->string('status')->after('type')->default('Active')->comment('The status of the playlist, such as Active, Inactive, or Scheduled.');

        // Timestamp indicating when the playlist should start playing
        // Allows for scheduling playlists to automatically start at a specific time.
        $table->dateTime('start_time')->after('status')->nullable()->comment('Scheduled start time for the playlist.');

        // Timestamp indicating when the playlist should stop playing
        // Allows for scheduling playlists to automatically end at a specific time.
        $table->dateTime('end_time')->after('start_time')->nullable()->comment('Scheduled end time for the playlist.');

        // An integer value to manage playlist priority
        // Higher values indicate a higher priority, useful when multiple playlists are active.
        $table->integer('priority')->after('end_time')->default(0)->comment('Priority of the playlist, with higher values indicating higher priority.');

        // Specifies how the playlist should behave when it reaches the end
        // Options like Repeat All, Repeat One, Shuffle, or Stop provide flexibility in playback behavior.
        $table->string('repeat_mode')->after('priority')->default('Repeat All')->comment('Behavior of the playlist when it reaches the end, such as Repeat All, Repeat One, Shuffle, or Stop.');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('channel_playlists', function (Blueprint $table) {
        $table->dropColumn(['type', 'status', 'start_time', 'end_time', 'priority', 'repeat_mode']);
      });
    }
};
