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
      Schema::table('mist_stream_wildcards', function (Blueprint $table) {
        $table->string('mime_type')->nullable()->after('source')->comment('MIME type of the stream or push');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('mist_stream_wildcards', function (Blueprint $table) {
        $table->dropColumn('mime_type');
      });
    }
};
