<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReorderMovieCategoriesIdInMovieCategorySubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movie_category_subs', function (Blueprint $table) {
          DB::statement('ALTER TABLE movie_category_subs MODIFY COLUMN movie_categories_id BIGINT UNSIGNED AFTER description');
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
            //
        });
    }
};
