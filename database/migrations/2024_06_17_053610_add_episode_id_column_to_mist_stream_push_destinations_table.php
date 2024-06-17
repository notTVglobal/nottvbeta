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
        Schema::table('mist_stream_push_destinations', function (Blueprint $table) {
          $table->unsignedBigInteger('episode_id')->nullable()->after('show_id')->index();
          $table->foreign('episode_id')->references('id')->on('show_episodes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mist_stream_push_destinations', function (Blueprint $table) {
          $table->dropForeign('episode_id');
          $table->dropColumn('episode_id');
        });
    }
};
