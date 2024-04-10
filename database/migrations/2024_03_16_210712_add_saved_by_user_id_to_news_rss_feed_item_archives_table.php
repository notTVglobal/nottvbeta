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
        $table->unsignedBigInteger('saved_by_user_id')->nullable()->after('extra_metadata');
        $table->foreign('saved_by_user_id')->references('id')->on('users')->onDelete('set null');
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
        $table->dropForeign(['saved_by_user_id']);
        $table->dropColumn('saved_by_user_id');
      });
    }
};
