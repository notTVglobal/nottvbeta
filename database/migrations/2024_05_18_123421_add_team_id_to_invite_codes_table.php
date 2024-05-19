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
        $table->unsignedBigInteger('team_id')->nullable()->after('updated_at');
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
        $table->dropColumn('team_id');
      });
    }
};
