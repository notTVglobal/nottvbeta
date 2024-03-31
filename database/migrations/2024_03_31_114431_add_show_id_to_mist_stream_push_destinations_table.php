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
      Schema::table('mist_stream_push_destinations', function (Blueprint $table) {
        $table->unsignedBigInteger('show_id')->nullable()->after('mist_stream_wildcard_id')->index();
        $table->foreign('show_id')->references('id')->on('shows')->onDelete('set null');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('mist_stream_push_destinations', function (Blueprint $table) {
        $table->dropForeign(['show_id']);
        $table->dropColumn(['show_id']);
      });
    }
};
