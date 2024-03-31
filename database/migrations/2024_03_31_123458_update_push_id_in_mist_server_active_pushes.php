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
      Schema::table('mist_server_active_pushes', function (Blueprint $table) {
        // Temporarily remove foreign key constraints if any. Adjust 'foreign_key_constraint_name' as needed.
//         $table->dropForeign('push_id');

        // Change the column type. You might need to handle existing data before this step.
        $table->unsignedBigInteger('push_id')->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('mist_server_active_pushes', function (Blueprint $table) {
        // Revert the column type back to string
        $table->string('push_id')->change();

        // Optionally, re-add any foreign key constraints removed in up()
//         $table->foreign('push_id')->references('id')->on('some_other_table');
      });
    }
};
