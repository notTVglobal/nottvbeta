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
        $table->string('stream_name')->default('0')->after('mist_stream_wildcard_id')->index();
        $table->string('full_push_uri')->default('0')->after('stream_name')->index();
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
        $table->dropIndex(['full_push_uri']); // Drop the index
        $table->dropIndex(['stream_name']); // Drop the index
        $table->dropColumn(['full_push_uri', 'stream_name']);
      });
    }
};
