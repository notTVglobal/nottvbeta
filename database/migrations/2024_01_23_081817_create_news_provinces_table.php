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
          $table->smallInteger('year_joined_confederation')->nullable();
          $table->integer('population')->nullable();
          $table->float('area')->nullable();
          $table->string('capital')->nullable();
          $table->string('time_zone')->nullable();
          $table->string('geo_coordinates')->nullable();
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
