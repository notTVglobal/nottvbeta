<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
          Schema::table('news_posts', function (Blueprint $table) {
            $table->dropForeign(['category']); // Drop the foreign key constraint
            $table->dropColumn('category'); // Then drop the column
            $table->dropColumn('city'); // Drop the column
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('news_posts', function (Blueprint $table) {
        $table->string('city')->nullable();
        $table->foreignId('category')->nullable()->constrained()->references('id')->on('news_categories');
      });
    }
};
