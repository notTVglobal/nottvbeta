<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::table('users', function (Blueprint $table) {
      $table->boolean('is_banned')->default(false);
      $table->timestamp('ban_expires_at')->nullable();
    });

    Schema::table('chat_messages', function (Blueprint $table) {
      $table->boolean('is_visible')->default(true);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('is_banned');
      $table->dropColumn('ban_expires_at');
    });

    Schema::table('chat_messages', function (Blueprint $table) {
      $table->dropColumn('is_visible');
    });
  }
};
