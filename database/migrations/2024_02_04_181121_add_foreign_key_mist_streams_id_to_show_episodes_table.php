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
//          $table->foreignId('mist_stream_id')->references('id')->on('mist_streams');
          $table->char('mist_stream_id', 26)->nullable();
          $table->foreign('mist_stream_id')->references('id')->on('mist_streams')->onDelete('set null');
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
          $table->dropForeign(['mist_stream_id']);
          $table->dropColumn('mist_streams_id');
        });
    }
};
