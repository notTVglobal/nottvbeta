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
        // Drop old columns
        $table->dropForeign(['show_id']);
        $table->dropForeign(['show_episode_id']);
        $table->dropForeign(['movie_id']);
        $table->dropForeign(['movie_trailer_id']);
        $table->dropForeign(['mist_stream_id']);

        $table->dropColumn(['show_id', 'show_episode_id', 'movie_id', 'movie_trailer_id', 'mist_stream_id', 'event_type']);

// Add new columns with comments
        $table->string('content_type')->nullable()->after('id')->comment('Type of content (show, episode, movie, etc.)');
        $table->unsignedBigInteger('content_id')->nullable()->after('content_type')->comment('Reference ID to the specific content');
        $table->boolean('recurrence_flag')->default(false)->after('end_time')->comment('Indicates if the schedule is for a recurring event');
        $table->unsignedBigInteger('recurrence_details_id')->nullable()->after('recurrence_flag')->comment('Reference ID to the recurrence pattern details');

// Add foreign key (ensure 'recurrence_details' table exists or adjust as necessary)
        $table->foreign('recurrence_details_id')->references('id')->on('recurrence_details')->onDelete('set null')->comment('Foreign key to recurrence details table');

        // Index for new content identification and recurrence flag
        $table->index(['content_type', 'content_id']);
        $table->index('recurrence_flag');
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
        // Remove newly added columns and foreign key
        $table->dropForeign(['recurrence_details_id']);
        $table->dropColumn(['content_type', 'content_id', 'recurrence_flag', 'recurrence_details_id']);

        // Re-add dropped columns
        // Re-add columns
        $table->foreignId('show_id')->nullable()->after('id');
        $table->foreignId('show_episode_id')->nullable()->after('show_id');
        $table->foreignId('movie_id')->nullable()->after('show_episode_id');
        $table->foreignId('movie_trailer_id')->nullable()->after('movie_id');
        $table->foreignUlid('mist_stream_id')->nullable()->after('movie_trailer_id');
        $table->string('event_type')->nullable()->after('mist_stream_id');

        // Explicitly define foreign key constraints
        $table->foreign('show_id')->references('id')->on('shows')->onDelete('set null');
        $table->foreign('show_episode_id')->references('id')->on('show_episodes')->onDelete('set null');
        $table->foreign('movie_id')->references('id')->on('movies')->onDelete('set null');
        $table->foreign('movie_trailer_id')->references('id')->on('movie_trailers')->onDelete('set null');
        // Note: Assuming 'mist_stream_id' references a column in a table named 'mist_streams'
        $table->foreign('mist_stream_id')->references('id')->on('mist_streams')->onDelete('set null');

        // Re-add indexes if they were previously there
        $table->index('show_id');
        $table->index('show_episode_id');
        $table->index('movie_id');
        $table->index('movie_trailer_id');
      });
    }
};
