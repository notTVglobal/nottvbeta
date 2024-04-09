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
        Schema::table('movies', function (Blueprint $table) {
            $table->foreignId('movie_category_id')->nullable()->constrained();
            $table->foreignId('movie_category_sub_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropForeign(['movie_category_id']);
            $table->dropColumn(['movie_category_id']);
            $table->dropForeign(['movie_category_sub_id']);
            $table->dropColumn(['movie_category_sub_id']);
        });
    }
};
