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
      Schema::table('movie_category_subs', function (Blueprint $table) {
        $table->dropUnique('movie_category_subs_name_unique');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('movie_category_subs', function (Blueprint $table) {
        $table->unique('name');
      });
    }
};
