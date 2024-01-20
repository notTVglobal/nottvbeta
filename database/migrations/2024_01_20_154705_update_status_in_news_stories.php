<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatusInNewsStories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Update existing rows where status is NULL
      DB::table('news_stories')
          ->whereNull('status')
          ->update(['status' => 1]);

      // Modify the status column
      Schema::table('news_stories', function (Blueprint $table) {
        $table->unsignedBigInteger('status')->default(1)->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      // Revert the status column to nullable without a default
      Schema::table('news_stories', function (Blueprint $table) {
        $table->unsignedBigInteger('status')->nullable()->default(null)->change();
      });

      // Optionally: Reset the status of articles that were originally NULL
      // if you need to maintain the original state
      // DB::table('news_stories')
      //    ->where('status', 1)
      //    ->update(['status' => null]);
    }
};
