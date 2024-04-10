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
        $table->string('mist_stream_wildcard_id', 36)->nullable()->after('mist_stream_id');

        $table->foreign('mist_stream_wildcard_id')
            ->references('id')->on('mist_stream_wildcards')
            ->onDelete('set null');
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
        $table->dropForeign('videos_mist_stream_wildcard_id_foreign');
        $table->dropColumn('mist_stream_wildcard_id');
      });
    }
};
