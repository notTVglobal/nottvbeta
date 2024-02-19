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
        Schema::table('app_settings', function (Blueprint $table) {
          $table->dropColumn(['mist_server_ip', 'mist_server_api_url', 'mist_server_username', 'mist_server_password', 'mist_access_control_secret']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->string('mist_server_ip')->nullable();
            $table->string('mist_server_api_url')->nullable();
            $table->string('mist_server_username')->nullable();
            $table->text('mist_server_password')->nullable();
            $table->string('mist_access_control_secret')->nullable();
        });
    }
};
