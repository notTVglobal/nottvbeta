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
        $table->bigInteger('bytes_recorded')->change();
        $table->bigInteger('total_milliseconds_recorded')->change();
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
        // Assuming the original columns were INT
        $table->integer('bytes_recorded')->change();
        $table->integer('total_milliseconds_recorded')->change();
      });
    }
};
