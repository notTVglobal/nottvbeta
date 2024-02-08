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
      Schema::create('other_contents', function (Blueprint $table) {
        $table->id()->comment('The primary key for the table.');
        $table->string('title')->comment('The title of the content.');
        $table->string('slug')->unique()->comment('A URL-friendly slug derived from the content title.');
        $table->text('description')->nullable()->comment('A detailed description of the content.');
        $table->string('type')->comment('The type of content (e.g., PSA, Promo, Filler, Interstitial), used for categorization.');
        $table->integer('duration')->nullable()->comment('The duration of the content in seconds.');
        $table->boolean('active')->default(true)->comment('A boolean flag indicating if the content is active or inactive.');

        // Foreign key to show_categories table
        $table->unsignedBigInteger('show_category_id')->nullable()->comment('References the show_categories table to categorize the content into a main category.');
        $table->foreign('show_category_id', 'other_contents_show_category_id_foreign')
            ->references('id')->on('show_categories')
            ->onDelete('set null')->comment('Sets the show_category_id to null on deletion of the referenced category.');

        // Foreign key to show_category_subs table
        $table->unsignedBigInteger('show_category_sub_id')->nullable()->comment('References the show_category_subs table for more specific categorization of the content.');
        $table->foreign('show_category_sub_id', 'other_contents_show_category_sub_id_foreign')
            ->references('id')->on('show_category_subs')
            ->onDelete('set null')->comment('Sets the show_category_sub_id to null on deletion of the referenced sub-category.');

        // Foreign key to images table
        $table->unsignedBigInteger('image_id')->nullable()->comment('References the images table, linking to a specific image.');
        $table->foreign('image_id', 'other_contents_image_id_foreign')
            ->references('id')->on('images')
            ->onDelete('set null')->comment('Sets the image_id to null on deletion of the referenced image.');

        // Foreign key to videos table
        $table->unsignedBigInteger('video_id')->nullable()->comment('Links the content to a specific video in the videos table, if applicable.');
        $table->foreign('video_id', 'other_contents_video_id_foreign')
            ->references('id')->on('videos')
            ->onDelete('set null')->comment('Sets the video_id to null on deletion of the referenced video.');

        // Foreign key to users table
        $table->unsignedBigInteger('user_id')->nullable()->comment('Associates the content with a specific user, indicating ownership or creation.');
        $table->foreign('user_id', 'other_contents_user_id_foreign')
            ->references('id')->on('users')
            ->onDelete('set null')->comment('Sets the user_id to null on deletion of the referenced user.');

        // JSON column for storing additional metadata
        $table->json('metadata')->nullable()->comment('A JSON column for storing additional metadata about the content.');

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
      Schema::dropIfExists('other_contents');
    }
};
