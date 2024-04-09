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
      Schema::table('shows', function (Blueprint $table) {
        // Drop the existing foreign key constraint
        // Laravel automatically generates the name based on the table and column names
        $table->dropForeign(['show_runner']); // Here, 'show_runner' is the name of the column

        // Make the column nullable and change its name if needed or just ensure it can be set to null
        $table->unsignedBigInteger('show_runner')->nullable()->change();

        // Re-add the foreign key constraint to the 'creators' table, set to null on delete
        $table->foreign('show_runner')->references('id')->on('creators')->onDelete('set null');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
      {
        Schema::table('shows', function (Blueprint $table) {
          //
        });
      }
    }
};
