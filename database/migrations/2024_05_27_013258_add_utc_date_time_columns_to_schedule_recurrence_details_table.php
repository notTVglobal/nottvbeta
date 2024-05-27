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
        $table->dateTime('start_dateTime_utc')->nullable()->after('timezone');
        $table->dateTime('end_dateTime_utc')->nullable()->after('start_dateTime_utc');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('schedule_recurrence_details', function (Blueprint $table) {
        $table->dropColumn(['start_dateTime_utc', 'end_dateTime_utc']);
      });
    }
};
