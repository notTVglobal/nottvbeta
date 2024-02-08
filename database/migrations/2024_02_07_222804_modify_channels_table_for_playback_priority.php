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
      Schema::table('channels', function (Blueprint $table) {
        // Drop the existing columns
        $table->dropColumn('isLive');
        $table->dropForeign(['current_video_id']);
        $table->dropColumn('current_video_id');
        $table->dropColumn('stream');

        // Add new foreign key columns

        $table->unsignedBigInteger('channel_playlist_id')->nullable()->after('channel_source_id');
        $table->foreign('channel_playlist_id')->references('id')->on('channel_playlists');

        $table->string('mist_stream_id')->nullable()->after('channel_playlist_id'); // Ensure correct column ordering
        $table->foreign('mist_stream_id')->references('id')->on('mist_streams');

        // Add the new column for priority playback with a comment
        $table->string('playback_priority_type')->nullable()->comment('Indicates the priority source for playback: MistStream, ChannelPlaylist, or ChannelSource');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('channels', function (Blueprint $table) {
        // Revert adding the new columns and foreign keys
        $table->dropForeign(['mist_stream_id']);
        $table->dropColumn('mist_stream_id');
        $table->dropForeign(['channel_playlist_id']);
        $table->dropColumn('channel_playlist_id');

        // Revert adding the playback_priority_type column
        $table->dropColumn('playback_priority_type');

        // Re-add the dropped columns
        $table->tinyInteger('isLive')->after('id'); // Adjust placement as necessary
        $table->unsignedBigInteger('current_video_id')->nullable()->after('some_column'); // Adjust 'some_column' to correctly place it
        $table->foreign('current_video_id', 'channels_current_video_id_foreign') // Ensure the foreign key constraint name matches
        ->references('id')->on('some_table'); // Replace 'some_table' with the correct table name
        $table->string('stream', 255)->nullable()->after('current_video_id');
      });
    }
};
