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
            $table->string('name')->unique()->nullable(false);
            $table->string('comment')->nullable();
            $table->string('source')->default('push://');
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
            $table->dropColumn('name');
            $table->dropColumn('comment');
            $table->dropColumn('source');
        });
    }
};
