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
            $table->string('mist_server_ip')->nullable(); // Assuming nullable
            $table->string('mist_server_api_url')->nullable(); // Assuming nullable
            $table->string('mist_server_username')->nullable(); // Assuming nullable
            $table->text('mist_server_password')->nullable(); // Assuming nullable
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
            $table->dropColumn('mist_server_ip');
            $table->dropColumn('mist_server_api_url');
            $table->dropColumn('mist_server_username');
            $table->dropColumn('mist_server_password');
        });
    }
};
