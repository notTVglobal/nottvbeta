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
      // Drop the foreign key constraint from the 'channels' table
      Schema::table('channels', function (Blueprint $table) {
        $table->dropForeign(['channel_source_id']); // Adjust the name 'channel_source_id' if it's different in your schema
      });

      // Rename the 'channel_sources' table to 'channel_external_sources'
      Schema::rename('channel_sources', 'channel_external_sources');

      // Recreate the foreign key constraint in 'channels' table for the renamed table
      Schema::table('channels', function (Blueprint $table) {
        $table->foreign('channel_source_id')->references('id')->on('channel_external_sources')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      // Rollback steps: Recreate the original state before the up() migration
      Schema::table('channels', function (Blueprint $table) {
        $table->dropForeign(['channel_source_id']);
      });

      Schema::rename('channel_external_sources', 'channel_sources');

      Schema::table('channels', function (Blueprint $table) {
        $table->foreign('channel_source_id')->references('id')->on('channel_sources')->onDelete('cascade');
      });
    }

};
