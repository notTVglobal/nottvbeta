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
        // Add the status_id column
        $table->unsignedBigInteger('status_id')->default(1);

        // Add foreign key constraint
        $table->foreign('status_id')
            ->references('id')
            ->on('show_statuses');
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
        // Remove foreign key constraint
        $table->dropForeign(['status_id']);

        // Remove the status_id column
        $table->dropColumn('status_id');
      });
    }
};
