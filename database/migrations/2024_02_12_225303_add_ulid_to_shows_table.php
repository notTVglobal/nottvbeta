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
        Schema::table('shows', function (Blueprint $table) {
          Schema::table('shows', function (Blueprint $table) {
            // Add the ulid column
            $table->char('ulid', 26)->unique()->nullable()->after('id');
          });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('shows', function (Blueprint $table) {
        // Remove the ulid column if it exists
        $table->dropColumn('ulid');
      });
    }
};
