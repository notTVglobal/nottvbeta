<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
      // Check if the table is empty before attempting to populate it
      if (DB::table('user_video_settings')->count() == 0) {
        $users = DB::table('users')->get(['id']);

        foreach ($users as $user) {
          DB::table('user_video_settings')->insert([
              'user_id' => $user->id,
          ]);
        }
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
