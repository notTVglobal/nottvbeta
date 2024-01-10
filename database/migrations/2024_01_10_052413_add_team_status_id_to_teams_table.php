<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamStatusIdToTeamsTable extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedBigInteger('team_status_id')->nullable()->after('id')->default(1);
            $table->foreign('team_status_id')->references('id')->on('team_statuses')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['team_status_id']);
            $table->dropColumn('team_status_id');
        });
    }
};
