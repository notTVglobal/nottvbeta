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
        Schema::create('news_provinces', function (Blueprint $table) {
          $table->id();
          $table->string('name');
          $table->string('abbreviation');
          // Additional fields
          $table->foreignId('country_id')->references('id')->on('news_countries');
          $table->smallInteger('year_joined_confederation')->nullable();
          $table->integer('population')->nullable();
          $table->float('area')->nullable();
          $table->decimal('latitude', 10, 8)->nullable(); // Approximate latitude of the province's centroid
          $table->decimal('longitude', 11, 8)->nullable(); // Approximate longitude of the province's centroid
          $table->string('capital')->nullable();
          $table->string('province_premier')->nullable(); // Current premier of the province
          $table->string('provinces_website')->nullable(); // Official website of the province
          $table->string('time_zone')->nullable(); // Time zone of the province (may vary in places)
          $table->float('gmt_offset')->nullable(); // GMT offset
          $table->float('gmt_offset_dst')->nullable(); // GMT offset during Daylight Saving Time
          $table->boolean('dst_observed')->nullable()->default(false); // Boolean for whether DST is observed
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_provinces');
    }
};
