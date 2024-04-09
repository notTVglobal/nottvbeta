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
      Schema::table('news_stories', function (Blueprint $table) {
        // Add the news_person_id column as an unsigned big integer
        $table->unsignedBigInteger('news_person_id')->nullable()->after('user_id');

        // Define the foreign key relationship
        $table->foreign('news_person_id')->references('id')->on('news_people')->onDelete('set null');
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
        // Drop the foreign key constraint
        $table->dropForeign(['news_person_id']);

        // Drop the news_person_id column
        $table->dropColumn('news_person_id');
      });
    }
};
