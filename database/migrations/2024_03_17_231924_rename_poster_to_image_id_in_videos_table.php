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
      Schema::table('videos', function (Blueprint $table) {
        // Drop the existing foreign key constraint, if necessary
        // This step depends on your database and current constraint naming
         $table->dropForeign(['poster']); // Adjust with the actual constraint name if named differently

        // Rename the column
        $table->renameColumn('poster', 'image_id');

        // Recreate the foreign key constraint with the new column name, if necessary
        // This assumes you have a 'images' table with an 'id' column
         $table->foreign('image_id')->references('id')->on('images');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
          $table->dropForeign(['image_id']); // Adjust with the actual constraint name if named differently

          // Rename the column
          $table->renameColumn('image_id', 'poster');

          // Recreate the foreign key constraint with the new column name, if necessary
          // This assumes you have a 'images' table with an 'id' column
          $table->foreign('poster')->references('id')->on('images');
        });
    }
};
