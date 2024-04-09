<?php

use Carbon\Carbon;
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
    Schema::table('movies', function (Blueprint $table) {
      // Add the copyrightYear column
      $table->integer('copyrightYear')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('movies', function (Blueprint $table) {
      // Remove the copyrightYear column
      $table->dropColumn('copyrightYear');
    });
  }
};
