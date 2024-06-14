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
        $table->unsignedBigInteger('next_playlist_id')->nullable()->after('repeat_mode');
        $table->foreign('next_playlist_id')->references('id')->on('channel_playlists')->onDelete('set null');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('channel_playlists', function (Blueprint $table) {
        $table->dropForeign(['next_playlist_id']);
        $table->dropColumn('next_playlist_id');
      });
    }
};
