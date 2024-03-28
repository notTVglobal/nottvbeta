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
        // Adding a new 'public_stats_url' column that can be nullable
        $table->string('public_stats_url')->nullable();
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
        // Remove the 'public_stats_url' column if the migration is rolled back
        $table->dropColumn('public_stats_url');
      });
    }
};
