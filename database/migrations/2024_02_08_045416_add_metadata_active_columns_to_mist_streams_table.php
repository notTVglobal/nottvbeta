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
    Schema::table('mist_streams', function (Blueprint $table) {
      $table->boolean('active')->default(true)->after('comment')->comment('A boolean flag indicating if the content is active or inactive.');
      $table->json('metadata')->nullable()->after('active')->comment('A JSON column for storing additional metadata about the mist stream wildcard.');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('mist_streams', function (Blueprint $table) {
      $table->dropColumn('active');
      $table->dropColumn('metadata');
    });
  }
};
