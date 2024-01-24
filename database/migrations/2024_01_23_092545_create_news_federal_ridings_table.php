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
      Schema::create('news_federal_ridings', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('news_province_id')->references('id')->on('news_provinces');
        $table->integer('population')->nullable();
        $table->float('area')->nullable();
        $table->string('representative')->nullable();
        $table->string('geo_coordinates')->nullable();
        $table->json('geo_shape_json')->nullable();
        $table->text('historical_data')->nullable();
        $table->string('economic_indicators')->nullable();
        $table->date('date_founded')->nullable();
        $table->integer('year_updated')->nullable();
        $table->integer('ed_code')->nullable();
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
        Schema::dropIfExists('news_federal_ridings');
    }
};
