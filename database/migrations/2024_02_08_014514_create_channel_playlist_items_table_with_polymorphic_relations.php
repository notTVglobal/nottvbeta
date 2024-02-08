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
    Schema::create('channel_playlist_items', function (Blueprint $table) {
      $table->id()->comment('Primary key for the playlist items.');

      $table->unsignedBigInteger('playlist_id')->comment('Foreign key linking to the channel playlists.');
      $table->foreign('playlist_id')->references('id')->on('channel_playlists')->onDelete('cascade')->comment('Ensures playlist items are deleted if the parent playlist is deleted.');

      // Polymorphic columns to link to different content types
      $table->string('content_type')->comment('Stores the type of the related content model (e.g., ShowEpisode, Movie, MovieTrailer, NewsStory, OtherContent).');
      $table->unsignedBigInteger('content_id')->comment('Stores the ID of the related content.');

      $table->integer('order')->default(0)->comment('Determines the display order of items within the playlist, with lower values indicating higher priority for playback.');
      $table->json('custom_playback_options')->nullable()->comment('Optional JSON column for any custom playback options relevant to the playlist item.');
      $table->json('metadata')->nullable()->comment('Flexible storage for item-specific data like user preferences, tagging, content relationships, analytics, and technical metadata, excluding custom playback settings.');


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
      Schema::dropIfExists('channel_playlist_items');
    }
};
