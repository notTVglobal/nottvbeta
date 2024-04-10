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

    Schema::table('shows', function (Blueprint $table) {
      // Drop foreign key constraint
      $table->dropForeign(['status_id']);
      // Drop the column
      $table->dropColumn('status_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('shows', function (Blueprint $table) {
      // Add the column back in the down() method
      $table->unsignedBigInteger('status_id')->nullable();
      // Recreate the foreign key constraint
      $table->foreign('status_id')->references('id')->on('statuses');
    });
  }
};
