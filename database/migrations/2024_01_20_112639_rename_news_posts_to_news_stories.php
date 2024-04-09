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
    Schema::table('news_posts', function (Blueprint $table) {
      $table->dropUnique('news_posts_slug_unique');
      $table->dropUnique('news_posts_title_unique');
      $table->dropForeign(['user_id']);
      $table->dropForeign(['image_id']);
      $table->dropForeign(['status']);
      $table->dropForeign(['video_id']);
    });

    Schema::rename('news_posts', 'news_stories');

    Schema::table('news_stories', function (Blueprint $table) {
      $table->unique('slug', 'news_stories_slug_unique');
      $table->unique('title', 'news_stories_title_unique');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
      $table->foreign('image_id')->references('id')->on('images');
      $table->foreign('status')->references('id')->on('news_statuses')->onDelete('set null');
      $table->foreign('video_id')->references('id')->on('videos')->onDelete('set null');
    });
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
  public function down()
  {
    Schema::table('news_stories', function (Blueprint $table) {
      $table->dropUnique('news_stories_slug_unique');
      $table->dropUnique('news_stories_title_unique');
      $table->dropForeign(['user_id']);
      $table->dropForeign(['image_id']);
      $table->dropForeign(['video_id']);
    });

    Schema::rename('news_stories', 'news_posts');

    Schema::table('news_posts', function (Blueprint $table) {
      $table->unique('slug', 'news_posts_slug_unique');
      $table->unique('title', 'news_posts_title_unique');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
      $table->foreign('image_id')->references('id')->on('images');
      $table->foreign('status')->references('id')->on('news_statuses')->onDelete('set null');
      $table->foreign('video_id')->references('id')->on('videos')->onDelete('set null');
    });
  }
};
