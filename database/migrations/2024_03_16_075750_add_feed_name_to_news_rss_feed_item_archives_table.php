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
      Schema::table('news_rss_feed_item_archives', function (Blueprint $table) {
        $table->string('feedName')->nullable()->after('id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('news_rss_feed_item_archives', function (Blueprint $table) {
        $table->dropColumn('feedName');
      });
    }
};
