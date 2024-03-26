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
      Schema::table('mist_stream_wildcards', function (Blueprint $table) {
        $table->boolean('is_recording')->default(0)->after('active')->comment('A boolean flag indicating if the wildcard stream is actively recording.');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('mist_stream_wildcards', function (Blueprint $table) {
        $table->dropColumn('is_recording');
      });
    }
};
