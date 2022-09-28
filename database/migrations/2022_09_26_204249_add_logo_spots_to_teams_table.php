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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('logo')->default('https://cdn.not.tv/wp-content/uploads/2022/09/27220247/Ping.png');
            $table->integer('memberSpots')->default(1);
            $table->integer('totalSpots')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('logo');
            $table->dropColumn('memberSpots');
            $table->dropColumn('totalSpots');
        });
    }
};
