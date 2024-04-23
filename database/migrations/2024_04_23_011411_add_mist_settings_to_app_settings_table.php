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
          $table->json('mist_server_settings')->nullable()->after('mist_server_rtmp_uri'); // Adds a JSON column that can be nullable
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
          $table->dropColumn('mist_server_settings'); // Make sure to provide a way to revert the migration
        });
    }
};
