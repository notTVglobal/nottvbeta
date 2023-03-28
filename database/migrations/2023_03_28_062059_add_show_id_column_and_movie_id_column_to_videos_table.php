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
        Schema::table('videos', function (Blueprint $table) {
            $table->foreignId('show_episodes_id')->nullable()->default(null)->constrained();
            $table->foreignId('movies_id')->nullable()->default(null)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['show_episodes_id']);
            $table->dropColumn('show_episodes_id');
            $table->dropForeign(['movies_id']);
            $table->dropColumn('movies_id');
        });
    }
};
