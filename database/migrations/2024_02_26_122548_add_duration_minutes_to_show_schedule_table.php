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
        Schema::table('show_schedule', function (Blueprint $table) {
            $table->integer('duration_minutes')->comment('Duration of the recurrence in minutes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_schedule', function (Blueprint $table) {
            $table->dropColumn('duration_minutes');
        });
    }
};
