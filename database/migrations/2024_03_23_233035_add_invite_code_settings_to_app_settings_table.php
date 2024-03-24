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
        // Adds a JSON column for invite code settings. Ensure your database supports JSON columns.
        $table->json('invite_code_settings')->nullable();
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
        // Drops the invite code settings column if the migration is rolled back.
        $table->dropColumn('invite_code_settings');
      });
    }
};
