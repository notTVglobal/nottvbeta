<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::table('news_stories', function (Blueprint $table) {
        $table->integer('duration')->nullable()->comment('The duration of the content in seconds')->after('image_id');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('news_stories', function (Blueprint $table) {
        $table->dropColumn('duration');
      });
    }
};
