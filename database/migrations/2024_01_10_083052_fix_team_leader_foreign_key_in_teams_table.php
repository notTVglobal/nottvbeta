<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixTeamLeaderForeignKeyInTeamsTable extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            // Temporarily remove the foreign key constraint
            $table->dropForeign(['team_leader']); // Adjust this if the foreign key has a specific name

            // Change the column definition without dropping it
            $table->foreignId('team_leader')->nullable()->change();

            // Add the new foreign key constraint
            $table->foreign('team_leader')->references('id')->on('creators');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['team_leader']);

            // Change back the column definition
            $table->foreignId('team_leader')->nullable()->change();

            // Add back the original foreign key constraint
            $table->foreign('team_leader')->references('user_id')->on('creators');
        });
    }
};
