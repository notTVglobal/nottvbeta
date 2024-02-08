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
        // Explicitly drop the foreign key by its constraint name
//        $table->dropForeign('channels_channel_source_id_foreign'); // Replace with the actual constraint name if different

        // Rename the column from 'channel_source_id' to 'channel_external_source_id'
        $table->renameColumn('channel_source_id', 'channel_external_source_id');

        // Re-add the foreign key constraint with the correct name and references
        $table->foreign('channel_external_source_id', 'channels_channel_external_source_id_foreign')
            ->references('id')->on('channel_external_sources')
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
      Schema::table('channels', function (Blueprint $table) {
        // Rollback: Drop the new foreign key constraint
        $table->dropForeign(['channel_external_source_id']);

        // Rollback: Rename the column back to 'channel_source_id'
        $table->renameColumn('channel_external_source_id', 'channel_source_id');

        // Rollback: Re-add the original foreign key constraint if necessary
        // $table->foreign('channel_source_id', 'channels_channel_source_id_foreign')
        //       ->references('id')->on('original_table_name')
        //       ->onDelete('set null');
      });
    }
};
