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
      Schema::table('channels', function (Blueprint $table) {
        // Add a boolean 'active' column with a default value of false
        $table->boolean('active')->default(false);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('channels', function (Blueprint $table) {
        // Drop the 'active' column if rolling back the migration
        $table->dropColumn('active');
      });
    }
};
