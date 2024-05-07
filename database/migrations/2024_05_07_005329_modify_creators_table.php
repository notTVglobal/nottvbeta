<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('creators', function (Blueprint $table) {
        $table->dropColumn('profile_is_public'); // Drop the old column
        $table->json('settings')->nullable(); // Add the new JSON column
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('creators', function (Blueprint $table) {
        $table->dropColumn('settings');
        $table->boolean('profile_is_public')->default(false); // Restore the old column type
      });
    }
};
