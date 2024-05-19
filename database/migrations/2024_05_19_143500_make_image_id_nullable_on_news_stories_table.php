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

        // Add the foreign key constraint with ON DELETE SET NULL
        $table->foreign('image_id')
            ->references('id')
            ->on('images')
            ->onDelete('set null');
      });

      Schema::table('news_stories', function (Blueprint $table) {
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
        $table->unsignedBigInteger('image_id')->default(4)->change();
      });

      Schema::table('news_stories', function (Blueprint $table) {
        // Drop the modified foreign key constraint
        $table->dropForeign(['image_id']);

        // Add the original foreign key constraint
        $table->foreign('image_id')
            ->references('id')
            ->on('images')
            ->onDelete('restrict'); // Change this to whatever the original action was
      });
    }
};
