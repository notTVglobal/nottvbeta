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
      Schema::table('recordings', function (Blueprint $table) {
        $table->renameColumn('file_size', 'bytes_recorded');
        $table->renameColumn('duration', 'total_milliseconds_recorded');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('recordings', function (Blueprint $table) {
        $table->renameColumn('bytes_recorded', 'file_size');
        $table->renameColumn('total_milliseconds_recorded', 'duration');
      });
    }
};
