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
    Schema::rename('news_posts', 'news_stories');
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
  public function down()
  {
    Schema::rename('news_stories', 'news_posts');
  }
};
