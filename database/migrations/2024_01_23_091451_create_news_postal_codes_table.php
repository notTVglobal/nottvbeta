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
      Schema::create('news_postal_codes', function (Blueprint $table) {
        $table->id(); // Primary key
        $table->string('code'); // The postal code
        $table->unsignedBigInteger('news_city_id'); // Foreign key to NewsCities
        $table->foreign('news_city_id')->references('id')->on('news_cities');
        $table->string('city_section')->nullable(); // Specific section or neighborhood within the city
        $table->string('area_coverage')->nullable(); // Description of the area covered by the postal code
        $table->string('geo_coordinates')->nullable(); // Latitude and longitude of the postal code area
        $table->text('demographic_information')->nullable(); // Demographic information of the area
        $table->text('historical_data')->nullable(); // Historical data related to the postal code
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
        Schema::dropIfExists('news_postal_codes');
    }
};
