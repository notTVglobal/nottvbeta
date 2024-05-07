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
        // Change the default value of the profile_is_public column to 1
        $table->boolean('profile_is_public')->default(1)->change();
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
        // Revert the default value of the profile_is_public column to its previous state
        $table->boolean('profile_is_public')->default(0)->change();
      });
    }
};
