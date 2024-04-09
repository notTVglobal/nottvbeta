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
        Schema::table('show_episodes', function (Blueprint $table) {
            $table->string('video_thumbnail')->nullable();
            $table->string('video_file_url')->nullable();
            $table->longtext('video_file_embed_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_episodes', function (Blueprint $table) {
            $table->dropColumn('video_thumbnail');
            $table->dropColumn('video_file_url');
            $table->dropColumn('video_file_embed_code');
        });
    }
};
