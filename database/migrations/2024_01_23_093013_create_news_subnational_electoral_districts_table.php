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
      Schema::create('news_subnational_electoral_districts', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('province_id')->nullable()->references('id')->on('news_provinces');
        $table->foreignId('country_id')->nullable()->references('id')->on('news_countries');
        $table->unsignedBigInteger('federal_electoral_district_id')->nullable();
        $table->foreign('federal_electoral_district_id', 'subnat_elec_dist_fed_elec_dist_fk')
            ->references('id')
            ->on('news_federal_electoral_districts');
        $table->integer('population')->nullable();
        $table->float('area')->nullable();
        $table->string('representative')->nullable();
        $table->decimal('latitude', 10, 8)->nullable(); // Approximate latitude of the subnational electoral district's centroid
        $table->decimal('longitude', 11, 8)->nullable(); // Approximate longitude of the subnational electoral district's centroid
        $table->text('historical_data')->nullable();
        $table->string('economic_indicators')->nullable();
        $table->date('date_founded')->nullable();
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
        Schema::dropIfExists('news_subnational_electoral_districts');
    }
};
