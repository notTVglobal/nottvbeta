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
        $table->string('media_type')->nullable()->after('order')->comment('Media type, either "externalVideo" or "mistServer"');
        $table->string('source_path')->nullable()->after('media_type')->comment('Full URL to the source that will be played');
        $table->string('source_type')->nullable()->after('source_path')->comment('Type of the source, e.g., video/mp4 or application/x-mpeg-url');
        $table->boolean('is_live')->default(0)->after('source_type')->comment('isLive or VOD');
        $table->integer('current_viewers_count')->default(0)->after('is_live')->comment('Current number of viewers watching');
        $table->integer('max_viewers_count')->default(0)->after('current_viewers_count')->comment('Maximum number of viewers watched');
        $table->json('additional_sources')->nullable()->after('max_viewers_count')->comment('Additional sources, array of objects: sourceName, sourcePath, sourceType, sourceCurrentViewersCount, sourceMaxViewersCount');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
      Schema::table('channel_playlist_items', function (Blueprint $table) {
        $table->dropColumn('media_type');
        $table->dropColumn('source_path');
        $table->dropColumn('source_type');
        $table->dropColumn('is_live');
        $table->dropColumn('current_viewers_count');
        $table->dropColumn('max_viewers_count');
        $table->dropColumn('additional_sources');
      });
    }
};
