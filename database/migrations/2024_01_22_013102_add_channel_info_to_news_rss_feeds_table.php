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
        $table->string('title')->nullable()->after('url');
        $table->text('description')->nullable()->after('title');
        $table->string('language')->nullable()->after('description');
        $table->dateTime('last_build_date')->nullable()->after('language');
        $table->string('image_url')->nullable()->after('last_build_date');
        $table->string('link')->nullable()->after('image_url');
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
          $table->dropColumn(['title', 'description', 'language', 'last_build_date', 'image_url', 'link']);
        });
    }
};
