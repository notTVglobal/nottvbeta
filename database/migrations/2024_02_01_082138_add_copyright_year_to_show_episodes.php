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
    Schema::table('show_episodes', function (Blueprint $table) {
      $table->integer('copyrightYear')->nullable();
    });
  }

  public function down() {
    Schema::table('show_episodes', function (Blueprint $table) {
      $table->dropColumn('copyrightYear');
    });
  }
};
