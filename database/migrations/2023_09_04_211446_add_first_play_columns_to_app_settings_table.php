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
        Schema::table('app_settings', function (Blueprint $table) {
            $table->string('first_play_video_source')->nullable()->default(null);
            $table->string('first_play_video_source_type')->nullable()->default(null);
            $table->string('first_play_video_name')->nullable()->default(null);
            $table->foreignId('first_play_channel_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn('first_play_video_source');
            $table->dropColumn('first_play_video_source_type');
            $table->dropColumn('first_play_video_name');
            $table->dropColumn('first_play_channel_id');
        });
    }
};
