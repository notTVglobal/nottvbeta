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
        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign(['app_settings_id']);
            $table->dropColumn('app_settings_id');
            $table->foreignId('app_setting_id')->nullable()->default(1)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign(['app_setting_id']);
            $table->dropColumn('app_setting_id');
            $table->foreignId('app_settings_id')->nullable()->default(1)->constrained();

        });
    }
};
