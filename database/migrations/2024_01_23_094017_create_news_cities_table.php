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
      Schema::create('news_cities', function (Blueprint $table) {
        $table->id(); // Primary key
        $table->string('name'); // Name of the city
        $table->string('type'); // 'type' column to specify the category like city, town, etc.
        $table->foreignId('province_id')->nullable()->references('id')->on('news_provinces'); // Foreign key to NewsProvince
        $table->foreignId('country_id')->nullable()->references('id')->on('news_countries'); // Foreign key to NewsCountry
        $table->integer('population')->nullable(); // Population of the city
        $table->float('area')->nullable(); // Total area of the city in square kilometers
        $table->decimal('latitude', 10, 8)->nullable(); // Approximate latitude of the city's centroid
        $table->decimal('longitude', 11, 8)->nullable(); // Approximate longitude of the city's centroid
        $table->string('economic_indicators')->nullable(); // Economic indicators of the city
        $table->text('cultural_significance')->nullable(); // Cultural significance of the city
        $table->string('city_mayor')->nullable(); // Current mayor of the city
        $table->string('tourism_attractions')->nullable(); // Key tourism attractions in the city
        $table->string('city_website')->nullable(); // Official website of the city
        $table->smallInteger('year_incorporated')->nullable(); // Year the city was incorporated
        $table->string('airport_code')->nullable(); // IATA airport code
        $table->string('time_zone')->nullable(); // Time zone of the city
        $table->float('gmt_offset')->nullable(); // GMT offset
        $table->float('gmt_offset_dst')->nullable(); // GMT offset during Daylight Saving Time
        $table->boolean('dst_observed')->nullable()->default(false); // Boolean for whether DST is observed
        $table->timestamps(); // Timestamps for created_at and updated_at
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_cities');
    }
};
