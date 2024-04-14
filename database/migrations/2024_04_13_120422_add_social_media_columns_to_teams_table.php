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
        Schema::table('teams', function (Blueprint $table) {
          $table->string('www_url', 255)->nullable();
          $table->string('instagram_name', 255)->nullable();
          $table->string('telegram_url', 255)->nullable();
          $table->string('twitter_handle', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
          $table->dropColumn(['www_url', 'instagram_name', 'telegram_url', 'twitter_handle']);
        });
    }
};
