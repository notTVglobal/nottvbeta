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
            $table->foreignId('app_setting_id')->nullable()->default(1)->constrained();
            $table->string('cloud_folder')->nullable()->default(null);
            $table->string('folder')->nullable()->default(null);
            $table->string('storage_location')->nullable()->default(null);
            $table->string('temp_path')->nullable()->default(null);
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
            $table->dropForeign(['app_setting_id']);
            $table->dropColumn('app_setting_id');
            $table->dropColumn('cloud_folder');
            $table->dropColumn('folder');
            $table->dropColumn('storage_location');
            $table->dropColumn('temp_path');
        });
    }
};
