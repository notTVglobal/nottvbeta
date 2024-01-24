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
      Schema::create('news_countries', function (Blueprint $table) {
        $table->id(); // Primary key for the table

        $table->char('iso_alpha2_code', 2)->unique(); // ISO 3166-1 Alpha-2 country code
        $table->char('iso_alpha3_code', 3)->unique(); // ISO 3166-1 Alpha-3 country code
        $table->string('name', 100); // Full name of the country

        $table->double('area', 15, 2)->nullable(); // The total land area of the country in square kilometers
        $table->decimal('latitude', 10, 8)->nullable(); // Approximate latitude of the country's centroid
        $table->decimal('longitude', 11, 8)->nullable(); // Approximate longitude of the country's centroid
        $table->string('capital', 100)->nullable(); // Name of the country's capital city

        $table->unsignedBigInteger('population')->nullable(); // Estimated population
        $table->decimal('gdp', 15, 2)->nullable(); // Gross Domestic Product

        $table->string('government_type', 100)->nullable(); // Type of government
        $table->json('official_language')->nullable(); // Main official language(s)

        $table->string('continent', 50)->nullable(); // Continent where the country is located
        $table->string('region', 100)->nullable(); // Geopolitical or economic region
        $table->string('subregion', 100)->nullable(); // Geopolitical or economic subregion

        $table->string('currency_code', 10)->nullable(); // ISO currency code
        $table->string('calling_code', 10)->nullable(); // Telephone calling code
        $table->string('timezone', 50)->nullable(); // Primary timezone

        $table->string('flag_image_url', 255)->nullable(); // URL to an image of the country's flag
        $table->string('emblem_image_url', 255)->nullable(); // URL to the national emblem or coat of arms

        $table->string('wiki_url', 255)->nullable(); // URL to the country's Wikipedia page
        $table->text('description')->nullable(); // A brief description or notes about the country

        $table->boolean('is_active')->default(true); // To mark if the country is active or inactive

        $table->timestamps(); // Timestamps for record creation and last update
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_countries');
    }
};
