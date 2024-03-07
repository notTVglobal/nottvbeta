<?php

use App\Models\ShowEpisode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Uid\Ulid;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      ShowEpisode::whereNull('ulid')->chunkById(100, function ($episodes) {
        foreach ($episodes as $episode) {
          $episode->ulid = Ulid::generate();
          $episode->save();
        }
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('existing_show_episodes', function (Blueprint $table) {
            //
        });
    }
};
