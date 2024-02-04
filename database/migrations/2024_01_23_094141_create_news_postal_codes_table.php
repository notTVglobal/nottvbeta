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
        $table->foreignId('city_id')->nullable()->default(null)->references('id')->on('news_cities'); // Foreign key to NewsCity
        $table->foreignId('province_id')->nullable()->default(null)->references('id')->on('news_provinces'); // Foreign key to NewsProvince
        $table->foreignId('country_id')->nullable()->references('id')->on('news_countries'); // Foreign key to NewsCountry
        $table->foreignId('news_federal_electoral_district_id')->nullable()->default(null)->references('id')->on('news_federal_electoral_districts'); // Foreign key to FederalElectoralDistrict
        $table->foreignId('news_subnational_electoral_district_id')->nullable()->default(null)->references('id')->on('news_subnational_electoral_districts'); // Foreign key to SubnationalElectoralDistrict
        $table->string('city_section')->nullable(); // Specific section or neighborhood within the city
        $table->string('area_coverage')->nullable(); // Description of the area covered by the postal code
        $table->decimal('latitude', 10, 8)->nullable(); // Approximate latitude of the postal code's centroid
        $table->decimal('longitude', 11, 8)->nullable(); // Approximate longitude of the postal code's centroid
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
