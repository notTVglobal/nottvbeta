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
        Schema::table('channel_playlist_items', function (Blueprint $table) {
          $table->timestamp('start_dateTime')->nullable();
          $table->timestamp('end_dateTime')->nullable();
          $table->integer('hasPlayed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channel_playlist_items', function (Blueprint $table) {
          $table->dropColumn(['start_dateTime', 'end_dateTime', 'hasPlayed']);
        });
    }
};
