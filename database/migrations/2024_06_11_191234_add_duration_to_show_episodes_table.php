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
      Schema::table('show_episodes', function (Blueprint $table) {
        $table->integer('duration')->nullable()->comment('The duration of the content in seconds')->after('release_dateTime');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('show_episodes', function (Blueprint $table) {
        $table->dropColumn('duration');
      });
    }
};
