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
        Schema::table('episodes', function (Blueprint $table) {
                $table->foreignId('episodeStatus_id')->default(1)->constrained();
                /* Status: 1=>Development, 2=>PreProduction, 3=>Production, 4=>Post-Production */
                /* 5=>Review 6=>Scheduled 7=>Published 8=>Archived 9=>Frozen 10=>Restricted */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('episodes', function (Blueprint $table) {
             $table->dropForeign('episodeStatus_id');
        });
    }
};
