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
        Schema::table('shows', function (Blueprint $table) {
            $table->foreignId('show_category_id')->nullable()->constrained();
            $table->foreignId('show_category_sub_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->dropForeign(['show_category_id']);
            $table->dropColumn(['show_category_id']);
            $table->dropForeign(['show_category_sub_id']);
            $table->dropColumn(['show_category_sub_id']);
        });
    }
};
