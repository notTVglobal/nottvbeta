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
        // Drop the existing foreign key constraint
        $table->dropForeign(['image_id']);
      });

      Schema::table('news_stories', function (Blueprint $table) {
          // First, make the image_id column nullable
          $table->unsignedBigInteger('image_id')->nullable()->change();
      });

      Schema::table('news_stories', function (Blueprint $table) {
        // Re-add the foreign key constraint with on delete set null
        $table->foreign('image_id')->references('id')->on('images')->onDelete('set null');
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
        // Drop the foreign key constraint with on delete set null
        $table->dropForeign(['image_id']);

        // Add the original foreign key constraint
        $table->foreign('image_id')
            ->references('id')
            ->on('images')
            ->onDelete('restrict'); // Change this to whatever the original action was
      });
    }
};
