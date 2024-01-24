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
        $table->unsignedBigInteger('news_category_id')->nullable()->after('slug');
        $table->foreign('news_category_id')->references('id')->on('news_categories');
        $table->unsignedBigInteger('news_category_sub_id')->nullable()->after('news_category_id');
        $table->foreign('news_category_sub_id')->references('id')->on('news_category_subs');
        $table->unsignedBigInteger('news_city_id')->nullable()->after('content');
        $table->foreign('news_city_id')->references('id')->on('news_cities');
        $table->unsignedBigInteger('news_province_id')->nullable()->after('news_city_id');
        $table->foreign('news_province_id')->references('id')->on('news_provinces');
        $table->unsignedBigInteger('news_federal_riding_id')->nullable()->after('news_province_id');
        $table->foreign('news_federal_riding_id')->references('id')->on('news_federal_ridings');
        $table->unsignedBigInteger('news_mla_riding_id')->nullable()->after('news_federal_riding_id');
        $table->foreign('news_mla_riding_id')->references('id')->on('news_mla_ridings');
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
        $table->dropForeign(['news_category_id']);
        $table->dropColumn('news_category_id');
        $table->dropForeign(['news_category_sub_id']);
        $table->dropColumn('news_category_sub_id');
        $table->dropForeign(['news_city_id']);
        $table->dropColumn('news_city_id');
        $table->dropForeign(['news_province_id']);
        $table->dropColumn('news_province_id');
        $table->dropForeign(['news_federal_riding_id']);
        $table->dropColumn('news_federal_riding_id');
        $table->dropForeign(['news_mla_riding_id']);
        $table->dropColumn('news_mla_riding_id');
//        $table->string('city')->after('content'); // Add back the 'city' column
//        $table->unsignedBigInteger('category')->nullable()->after('city');  // Add back the 'category' column
//        $table->foreign('category')->references('id')->on('news_categories');
      });
    }
};
