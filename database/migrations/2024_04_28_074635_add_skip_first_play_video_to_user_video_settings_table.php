<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('user_video_settings', function (Blueprint $table) {
      $table->boolean('skip_first_play_video')->default(0)->after('user_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('user_video_settings', function (Blueprint $table) {
      $table->dropColumn('skip_first_play_video');
    });
  }
};
