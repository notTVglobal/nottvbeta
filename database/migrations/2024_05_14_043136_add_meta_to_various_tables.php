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
      Schema::table('movies', function (Blueprint $table) {
        $table->json('meta')->nullable()->after('release_date'); // Adjust 'existing_column' as needed
      });

      Schema::table('movie_trailers', function (Blueprint $table) {
        $table->json('meta')->nullable()->after('file_url'); // Adjust 'existing_column' as needed
      });

      Schema::table('show_episodes', function (Blueprint $table) {
        $table->json('meta')->nullable()->after('isPublished'); // Adjust 'existing_column' as needed
      });

      Schema::table('news_stories', function (Blueprint $table) {
        $table->json('meta')->nullable()->after('status'); // Adjust 'existing_column' as needed
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('movies', function (Blueprint $table) {
        $table->dropColumn('meta');
      });

      Schema::table('movie_trailers', function (Blueprint $table) {
        $table->dropColumn('meta');
      });

      Schema::table('show_episodes', function (Blueprint $table) {
        $table->dropColumn('meta');
      });

      Schema::table('news_stories', function (Blueprint $table) {
        $table->dropColumn('meta');
      });
    }
};
