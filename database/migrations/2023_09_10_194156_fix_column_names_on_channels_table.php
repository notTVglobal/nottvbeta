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
        Schema::table('channels', function (Blueprint $table) {
            $table->dropForeign(['source']);
            $table->dropColumn('source');
            $table->foreignId('channel_source_id')->nullable()->default(null)->constrained()->references('id')->on('channel_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropForeign(['channel_source_id']);
            $table->dropColumn('channel_source_id');
            $table->foreignId('source')->nullable()->default(null)->constrained()->references('id')->on('channel_sources');
        });
    }
};
