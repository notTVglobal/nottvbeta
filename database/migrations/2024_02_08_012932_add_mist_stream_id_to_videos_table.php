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
        // Add the mist_stream_id column as a nullable string after video_url
        // Assuming mist_stream_id is a ULID, it's stored as a string
        $table->string('mist_stream_id', 36)->nullable()->after('video_url')->comment('References a ULID in the mist_streams table');

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
      Schema::table('videos', function (Blueprint $table) {
        $table->dropForeign(['mist_stream_id']);
        $table->dropColumn('mist_stream_id');
      });
    }
};
