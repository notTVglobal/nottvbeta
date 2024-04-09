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
      Schema::table('news_postal_codes', function (Blueprint $table) {
        // Add an index to the 'code' column
        $table->index('code');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('news_postal_codes', function (Blueprint $table) {
        // Remove the index from the 'code' column
        $table->dropIndex(['code']);
      });
    }
};
