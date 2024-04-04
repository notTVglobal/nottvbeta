<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
      // Recreate the index with a prefix length, if necessary
      Schema::table('mist_stream_push_destinations', function (Blueprint $table) {
        // Drop the existing index
        $table->dropIndex('mist_stream_push_destinations_full_push_uri_index');
      });

      Schema::table('mist_stream_push_destinations', function (Blueprint $table) {
        // Alter the column type to TEXT
        $table->text('full_push_uri')->change();
      });

      // Recreate the index with a prefix length, if necessary
      Schema::table('mist_stream_push_destinations', function (Blueprint $table) {
        $table->index([DB::raw('full_push_uri(191)')], 'mist_stream_push_destinations_full_push_uri_index');
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
        // First, if you added a prefix index in the up() method, drop it here
        $table->dropIndex('mist_stream_push_destinations_full_push_uri_index');

        // Then, change the column back to its original type
        // Assuming the original type was VARCHAR(255), for example
        $table->string('full_push_uri', 255)->change();
      });

      // Finally, if the original column was indexed, recreate that index
      // Note: Since we're assuming the original scenario did have an index and you dropped it in up(),
      // you should add it back here without specifying a prefix length
      Schema::table('mist_stream_push_destinations', function (Blueprint $table) {
        $table->index('full_push_uri', 'mist_stream_push_destinations_full_push_uri_index');
      });
    }
};
