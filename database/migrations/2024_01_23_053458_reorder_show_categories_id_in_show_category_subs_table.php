<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReorderShowCategoriesIdInShowCategorySubsTable  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('show_category_subs', function (Blueprint $table) {
          DB::statement('ALTER TABLE show_category_subs MODIFY COLUMN show_categories_id BIGINT UNSIGNED AFTER description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_category_subs', function (Blueprint $table) {
            //
        });
    }
};
