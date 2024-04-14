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
        Schema::table('schedules', function (Blueprint $table) {
          // Drop the index associated with the foreign key
          $table->dropIndex('show_schedule_recurrence_details_id_foreign');  // Adjust the index name if necessary

          // Using the new table name for the foreign key
          $table->foreign('recurrence_details_id')
              ->references('id')->on('schedule_recurrence_details')
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
      Schema::table('schedules', function (Blueprint $table) {
        // Drop the foreign key first before adding the index back
        // We must specify the foreign key constraint name if it was customized when added
        $table->dropForeign(['recurrence_details_id']);  // You may need to specify the constraint name if not default

        // Recreate the index that was dropped in the up() method
        $table->index('recurrence_details_id', 'show_schedule_recurrence_details_id_foreign');
      });
    }
};
