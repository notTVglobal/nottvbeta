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
        // Drop the existing foreign key if it exists
        $foreignKeys = \DB::getDoctrineSchemaManager()->listTableForeignKeys('news_stories');
        foreach ($foreignKeys as $key) {
          if ($key->getName() == 'news_stories_status_foreign') {
            $table->dropForeign('news_stories_status_foreign');
          }
        }

        $table->unsignedBigInteger('status')->default(1)->change();
        $table->foreign('status', 'news_stories_status_foreign')->references('id')->on('news_statuses');
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
        // Use the exact name of the foreign key
        $table->dropForeign('news_stories_status_foreign');
        $table->unsignedBigInteger('status')->nullable()->default(null)->change();
      });
    }
};
