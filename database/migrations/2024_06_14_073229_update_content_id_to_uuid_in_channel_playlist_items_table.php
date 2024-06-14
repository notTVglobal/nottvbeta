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
        // Modify the content_id column to char(36) to store UUIDs
        $table->string('content_id', 36)->change()->comment('Stores the ID of the related content.');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('channel_playlist_items', function (Blueprint $table) {
        // Revert the content_id column back to unsignedBigInteger
        $table->unsignedBigInteger('content_id')->change()->comment('Stores the ID of the related content.');
      });
    }
};
