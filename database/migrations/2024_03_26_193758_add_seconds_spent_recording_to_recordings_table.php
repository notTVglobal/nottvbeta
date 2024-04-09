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
        $table->bigInteger('seconds_spent_recording')->nullable()->after('bytes_recorded')->comment('Total seconds spent recording');      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('recordings', function (Blueprint $table) {
        $table->dropColumn('seconds_spent_recording');
      });
    }
};
