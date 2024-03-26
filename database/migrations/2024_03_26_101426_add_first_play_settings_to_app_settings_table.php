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
        // Add the JSON column 'first_play_settings' nullable and after 'cloud_folder'
        $table->json('first_play_settings')->nullable()->after('cloud_folder');
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
        // Remove the column if the migration is rolled back
        $table->dropColumn('first_play_settings');
      });
    }
};
