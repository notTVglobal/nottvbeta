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
      Schema::table('mist_streams', function (Blueprint $table) {
//        $table->ulid('id')->change();
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('mist_streams', function (Blueprint $table) {
//        $table->id('id')->change(); // Adds back the 'id' column as an auto-increment primary key
      });
    }
};
