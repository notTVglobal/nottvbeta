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
        // Drop the foreign key constraint
        $table->dropForeign(['image_id']);

        // Make the column nullable
        $table->unsignedBigInteger('image_id')->nullable()->change();
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
        // Make the column not nullable
        $table->unsignedBigInteger('image_id')->nullable(false)->change();

        // Re-add the foreign key constraint without on delete set null
        $table->foreign('image_id')->references('id')->on('images');
      });
    }
};
