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
        Schema::create('user_video_settings', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('user_id');
          $table->morphs('content');
          $table->double('last_playback_position')->default(0);
          $table->float('volume_setting')->default(1.0);
          $table->float('playback_speed')->default(1.0);
          $table->json('additional_settings')->nullable();  // JSON column for additional settings
          $table->timestamps();

          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_video_settings');
    }
};
