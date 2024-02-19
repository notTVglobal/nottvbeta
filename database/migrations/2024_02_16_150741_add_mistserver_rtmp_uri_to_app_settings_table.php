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
      // Add the 'mistserver_rtmp_uri' column after 'mist_server_uri'
      $table->string('mist_server_rtmp_uri', 2048)->nullable()->after('mist_server_uri');
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
      // Remove the 'mistserver_rtmp_uri' column if this migration is rolled back
      $table->dropColumn('mist_server_rtmp_uri');
    });
  }
};
