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
      Schema::table('image_hashes', function (Blueprint $table) {
        $table->index('hash', 'image_hashes_hash_index');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('image_hashes', function (Blueprint $table) {
        $table->dropIndex('image_hashes_hash_index');
      });
    }
};
