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
          $table->renameColumn('hasPlayed', 'has_played');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channel_playlist_items', function (Blueprint $table) {
          $table->renameColumn('has_played', 'hasPlayed');
        });
    }
};
