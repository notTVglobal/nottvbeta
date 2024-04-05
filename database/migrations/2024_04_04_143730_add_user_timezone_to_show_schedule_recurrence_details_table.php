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
      Schema::table('show_schedule_recurrence_details', function (Blueprint $table) {
        $table->string('timezone')->after('end_date')->default('UTC');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_schedule_recurrence_details', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });
    }
};
