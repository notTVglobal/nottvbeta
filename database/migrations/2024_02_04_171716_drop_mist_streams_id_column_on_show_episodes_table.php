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
      Schema::table('show_episodes', function (Blueprint $table) {
//          $table->dropForeign(['mist_stream_id']); // Adjust based on the actual constraint name
//          $table->dropColumn('mist_stream_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('show_episodes', function (Blueprint $table) {
//        $table->unsignedBigInteger('mist_stream_id')->nullable();
//        $table->foreign('mist_stream_id')->references('id')->on('mist_streams');
      });

    }
};
