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
      Schema::table('channel_playlists', function (Blueprint $table) {
        $table->renameColumn('start_time', 'start_dateTime');
        $table->renameColumn('end_time', 'end_dateTime');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('channel_playlists', function (Blueprint $table) {
        $table->renameColumn('start_dateTime', 'start_time');
        $table->renameColumn('end_dateTime', 'end_time');
      });
    }
};
