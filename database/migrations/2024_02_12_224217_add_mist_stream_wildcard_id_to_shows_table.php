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
        // Add the mist_stream_wildcard_id column as  or similar, depending on your needs
        $table->string('mist_stream_wildcard_id', 36)->nullable();

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
        Schema::table('shows', function (Blueprint $table) {
          $table->dropForeign('shows_mist_stream_wildcard_id_foreign');
          $table->dropColumn('mist_stream_wildcard_id');
        });
    }
};
