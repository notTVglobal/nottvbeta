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
      Schema::table('schedules_indexes', function (Blueprint $table) {
        // Add a JSON column 'next_broadcast_details' that is nullable
        // and placed after 'next_broadcast' column if it exists
        $table->json('next_broadcast_details')->nullable()->after('next_broadcast');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('schedules_indexes', function (Blueprint $table) {
        // Remove the 'next_broadcast_details' column
        $table->dropColumn('next_broadcast_details');
      });
    }
};
