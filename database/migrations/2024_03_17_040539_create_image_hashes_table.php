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
      Schema::create('image_hashes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('image_id')->unique(); // Reference to the images table
        $table->string('hash', 64); // Assuming SHA-256 for hashing
        $table->boolean('is_duplicate')->default(false); // Flag for duplicates
        $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
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
        Schema::dropIfExists('image_hashes');
    }
};
