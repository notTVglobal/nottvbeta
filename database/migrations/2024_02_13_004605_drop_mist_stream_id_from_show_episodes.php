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
      Schema::table('show_episodes', function (Blueprint $table) {
        // First, drop the foreign key constraint to avoid errors
        $table->dropForeign(['mist_stream_id']); // Use the actual constraint name if different

        // Then, drop the column
        $table->dropColumn('mist_stream_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('show_episodes', function (Blueprint $table) {
        // Recreate the column. Adjust the column definition as needed.
        $table->string('mist_stream_id', 36)->nullable();

        // Optionally, re-add the foreign key constraint
        // Ensure the referenced table and column exist and are correct.
        $table->foreign('mist_stream_id')
            ->references('id')->on('mist_streams')
            ->onDelete('set null'); // Adjust the onDelete action as needed
      });
    }
};
