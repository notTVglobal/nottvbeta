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
            $table->foreignId('category_id')->nullable()->constrained()->references('id')->on('movie_categories');
            $table->foreignId('sub_category_id')->nullable()->constrained()->references('id')->on('movie_category_subs');
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
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id']);
            $table->dropForeign(['sub_category_id']);
            $table->dropColumn(['sub_category_id']);
        });
    }
};
