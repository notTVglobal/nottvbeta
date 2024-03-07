<?php

use App\Models\Show;
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
      Show::whereNull('ulid')->chunkById(100, function ($shows) {
        foreach ($shows as $show) {
          $show->ulid = (string) Ulid::generate();
          $show->save();
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
        Schema::table('existing_shows', function (Blueprint $table) {
            //
        });
    }
};
