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
    Schema::table('show_schedule', function (Blueprint $table) {
      // First, correctly drop the foreign key, if it exists
      $table->dropForeign(['recurrence_details_id']);  // Adjust for the actual foreign key name, if necessary

      // Drop the column after dropping the foreign key
      $table->dropColumn('recurrence_details_id');
    });

    Schema::table('show_schedule', function (Blueprint $table) {
      // Re-add the column
      $table->unsignedBigInteger('recurrence_details_id')->nullable()->after('recurrence_flag')->comment('Reference ID to the recurrence pattern details');

      // Correctly reference the 'show_schedule_recurrence_details' table in the foreign key constraint
      $table->foreign('recurrence_details_id')->references('id')->on('show_schedule_recurrence_details')->onDelete('cascade');
    });
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
  public function down()
  {
    Schema::table('show_schedule', function (Blueprint $table) {
      // Drop the newly added foreign key and column
      $table->dropForeign(['recurrence_details_id']); // Use the new foreign key name
      $table->dropColumn('recurrence_details_id');
    });

    Schema::table('show_schedule', function (Blueprint $table) {
      // Optionally, re-add the original setup without cascade delete, as needed.
      // Note: Adjust this part according to your original schema before this migration.
      $table->unsignedBigInteger('recurrence_details_id')->nullable()->after('recurrence_flag')->comment('Reference ID to the recurrence pattern details');
      // Adjust the table name in the foreign key reference as originally configured
      $table->foreign('recurrence_details_id')->references('id')->on('show_schedule_recurrence_details')->onDelete('set null');

    });
  }
};
