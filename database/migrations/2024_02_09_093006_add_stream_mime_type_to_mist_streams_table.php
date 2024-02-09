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
      Schema::table('mist_streams', function (Blueprint $table) {
        // Adds a 'stream_mime_type' column to store the MIME type of the stream.
        // Common MIME types include 'application/vnd.apple.mpegurl' for HLS streams
        // and 'video/mp4' for MP4 video files. This column can accommodate a wide range
        // of MIME types to support various streaming formats.
        $table->string('mime_type')->nullable()->after('name')->comment('MIME type for the stream content, commonly \'application/vnd.apple.mpegurl\' for HLS or \'video/mp4\' for MP4 files.');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('mist_streams', function (Blueprint $table) {
        $table->dropColumn('mime_type');
      });
    }
};
