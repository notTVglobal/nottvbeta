<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtColumnsToMultipleTables extends Migration
{
    public function up()
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('show_episodes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('news_posts', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('show_episodes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('news_posts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
