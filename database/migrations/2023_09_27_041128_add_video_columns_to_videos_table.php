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
        Schema::table('videos', function (Blueprint $table) {
            $table->string('video_url')->nullable();
            $table->string('audio_codec')->nullable();
            $table->string('video_codec')->nullable();
            $table->integer('audio_channels')->nullable();
            $table->string('length')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'audio_codec', 'video_codec', 'audio_channels', 'length']);
        });
    }
};
