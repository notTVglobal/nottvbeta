<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowScheduleTable extends Migration
{
  public function up()
  {
    Schema::create('show_schedule', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('show_id')->nullable();
//      $table->unsignedBigInteger('mist_stream_id')->nullable();
      $table->unsignedBigInteger('show_episode_id')->nullable();
      $table->unsignedBigInteger('movie_id')->nullable();
      $table->unsignedBigInteger('movie_trailer_id')->nullable();
      $table->string('type')->comment('Discriminator: show, movie, trailer, live stream');
      $table->dateTime('start_time');
      $table->dateTime('end_time');
      $table->string('event_type')->default('one-time')->comment('Indicates if the event is one-time or recurring');
      $table->enum('status', ['scheduled', 'live', 'completed', 'cancelled']);
      $table->integer('priority')->default(0);
      $table->timestamps();

      // Foreign keys
      $table->foreign('show_id')->references('id')->on('shows')->onDelete('set null');
//      $table->foreign('mist_stream_id')->references('id')->on('mist_streams')->onDelete('set null');
      $table->foreign('show_episode_id')->references('id')->on('show_episodes')->onDelete('set null');
      $table->foreign('movie_id')->references('id')->on('movies')->onDelete('set null');
      $table->foreign('movie_trailer_id')->references('id')->on('movie_trailers')->onDelete('set null');

      // Indexes
      $table->index('start_time');
      $table->index('status');
      $table->index('type');
      $table->index('priority');
      $table->index('event_type'); // for performance on queries filtering by event_type
    });
  }

  public function down()
  {
    Schema::dropIfExists('show_schedule');
  }
};

