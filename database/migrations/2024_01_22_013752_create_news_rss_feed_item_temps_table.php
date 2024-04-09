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
        Schema::create('news_rss_feed_item_temps', function (Blueprint $table) {
          $table->id();
          $table->foreignId('news_rss_feed_id')->constrained()->onDelete('cascade');
          $table->string('title')->nullable();;
          $table->text('description')->nullable();;
          $table->string('link')->nullable();;
          $table->dateTime('pubDate')->nullable();;
          $table->string('image_url')->nullable();
          $table->boolean('is_saved')->nullable()->default(false);
          $table->json('extra_metadata')->nullable(); // JSON column for extra metadata
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_rss_feed_item_temps');
    }
};
