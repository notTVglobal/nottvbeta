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
        Schema::table('show_category_subs', function (Blueprint $table) {
            $table->foreignId('show_categories_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_category_subs', function (Blueprint $table) {
            $table->dropForeign(['show_categories_id']);
            $table->dropColumn(['show_categories_id']);
        });
    }
};
