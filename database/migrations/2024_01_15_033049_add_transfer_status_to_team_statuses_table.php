<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddTransferStatusToTeamStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert 'Transfer' status into 'team_statuses' table
        DB::table('team_statuses')->insert([
            'status' => 'transfer'
            // Add other columns as necessary
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Optionally, add logic to revert the migration if necessary
        DB::table('team_statuses')->where('status', '=', 'transfer')->delete();
    }
}
