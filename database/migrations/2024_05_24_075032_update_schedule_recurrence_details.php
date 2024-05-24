<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::table('schedule_recurrence_details', function (Blueprint $table) {
        // Remove boolean day columns
        $table->dropColumn(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
        // Drop start_dateTime column
        $table->dropColumn('start_time');
        // Rename columns
        $table->renameColumn('start_date', 'start_dateTime');
        $table->renameColumn('end_date', 'end_dateTime');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('schedule_recurrence_details', function (Blueprint $table) {
        // Add boolean day columns back
        $table->boolean('monday')->default(false);
        $table->boolean('tuesday')->default(false);
        $table->boolean('wednesday')->default(false);
        $table->boolean('thursday')->default(false);
        $table->boolean('friday')->default(false);
        $table->boolean('saturday')->default(false);
        $table->boolean('sunday')->default(false);
        // Rename columns back
        $table->renameColumn('start_dateTime', 'start_date');
        $table->renameColumn('end_dateTime', 'end_date');
        // Add start_dateTime column back
        $table->time('start_time')->nullable()->index()->change();
      });
    }
};
