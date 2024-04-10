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
        $table->foreignId('news_category_id')->nullable()->after('slug')->references('id')->on('news_categories');
        $table->foreignId('news_category_sub_id')->nullable()->after('news_category_id')->references('id')->on('news_category_subs');
        $table->foreignId('city_id')->nullable()->after('content')->references('id')->on('news_cities');
        $table->foreignId('province_id')->nullable()->after('city_id')->references('id')->on('news_provinces');
        $table->foreignId('country_id')->nullable()->after('province_id')->references('id')->on('news_countries');
        $table->foreignId('news_federal_electoral_district_id')->nullable()->after('country_id')->references('id')->on('news_federal_electoral_districts');
        $table->foreignId('news_subnational_electoral_district_id')->nullable()->after('news_federal_electoral_district_id')->references('id')->on('news_subnational_electoral_districts');
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
        $table->dropForeign(['city_id']);
        $table->dropColumn('city_id');
        $table->dropForeign(['province_id']);
        $table->dropColumn('province_id');
        $table->dropForeign(['news_federal_electoral_district_id']);
        $table->dropColumn('news_federal_electoral_district_id');
        $table->dropForeign(['news_subnational_electoral_district_id']);
        $table->dropColumn('news_subnational_electoral_district_id');
//        $table->string('city')->after('content'); // Add back the 'city' column
//        $table->unsignedBigInteger('category')->nullable()->after('city');  // Add back the 'category' column
//        $table->foreign('category')->references('id')->on('news_categories');
      });
    }
};
