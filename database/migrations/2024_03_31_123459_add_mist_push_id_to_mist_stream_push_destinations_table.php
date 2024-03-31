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
        $table->unsignedBigInteger('mist_push_id')->nullable()->after('show_id')->index();
        $table->foreign('mist_push_id')->references('push_id')->on('mist_server_active_pushes')->onDelete('set null');
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
//         Drop the foreign key constraint first
        $table->dropForeign(['mist_push_id']);

//         Then drop the column
        $table->dropColumn(['mist_push_id']);
      });
    }
};
