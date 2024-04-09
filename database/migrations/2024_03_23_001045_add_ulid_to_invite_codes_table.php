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
      Schema::table('invite_codes', function (Blueprint $table) {
        // Ensure that you have the `notes` column or adjust as needed for your table's structure
        $table->ulid('ulid')->after('notes')->nullable()->unique();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('invite_codes', function (Blueprint $table) {
        $table->dropColumn('ulid');
      });
    }
};
