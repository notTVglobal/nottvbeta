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
      // Add the mist_access_control_secret column as a string
      $table->string('mist_access_control_secret')->nullable();
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
      // Remove the mist_access_control_secret column
      $table->dropColumn('mist_access_control_secret');
    });
  }
};
