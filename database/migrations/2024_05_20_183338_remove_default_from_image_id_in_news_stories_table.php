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
        $table->unsignedBigInteger('image_id')->nullable()->default(null)->change();
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
        $table->unsignedBigInteger('image_id')->nullable()->default(4)->change();
      });
    }
};
