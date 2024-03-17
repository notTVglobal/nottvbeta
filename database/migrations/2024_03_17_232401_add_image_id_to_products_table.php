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
      Schema::table('products', function (Blueprint $table) {
        // Add the image_id column with a default value of 4
        $table->unsignedBigInteger('image_id')->default(4)->after('id');

        // Optional: Add a foreign key constraint to the images table
         $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('products', function (Blueprint $table) {
        // Optional: If you added a foreign key constraint, drop it first
         $table->dropForeign(['image_id']);

        // Remove the image_id column
        $table->dropColumn('image_id');
      });
    }
};
