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
        Schema::table('news_rss_feeds', function (Blueprint $table) {
          Schema::table('news_rss_feeds', function (Blueprint $table) {
            $table->dateTime('last_successful_update')->nullable()->after('link');
            $table->dateTime('last_attempt')->nullable()->after('last_successful_update');
          });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('news_rss_feeds', function (Blueprint $table) {
        $table->dropColumn('last_successful_update');
        $table->dropColumn('last_attempt');
      });
    }
};
