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
      Schema::table('news_rss_feed_item_temps', function (Blueprint $table) {
        // Add the saved_by_user_id column
        $table->unsignedBigInteger('saved_by_user_id')->nullable()->after('is_saved');

        // Add the foreign key constraint
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
      Schema::table('news_rss_feed_item_temps', function (Blueprint $table) {
        // Drop the foreign key constraint
        // The foreign key constraint name follows the Laravel convention of using the table name, the column name, and 'foreign' as a suffix
        // However, it's a good practice to check the exact name in your database or use the dropForeign method with an array
        $table->dropForeign(['saved_by_user_id']);

        // Drop the saved_by_user_id column
        $table->dropColumn('saved_by_user_id');
      });
    }
};
