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
      Schema::table('channels', function (Blueprint $table) {
        // Add an integer 'order' column. Consider making it unsigned if negative values are not needed
        $table->integer('sort_order')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('channels', function (Blueprint $table) {
        // Remove the 'order' column
        $table->dropColumn('order');
      });
    }
};
