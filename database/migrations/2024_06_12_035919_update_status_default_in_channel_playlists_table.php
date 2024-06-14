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
        $table->string('status')
            ->default('Inactive')
            ->comment('The status of the playlist, such as Active, Inactive, or Scheduled.')
            ->change();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('channel_playlists', function (Blueprint $table) {
        // Here you might want to revert to the original default, if known
        $table->string('status')
            ->default('Active') // Change this to the original default if it was different
            ->comment('The status of the playlist, such as Active, Inactive, or Scheduled.')
            ->change();
      });
    }
};
