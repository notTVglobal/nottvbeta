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
      Schema::table('user_video_settings', function (Blueprint $table) {
        // Change content_type and content_id to be nullable
        $table->string('content_type')->nullable()->change();
        $table->unsignedBigInteger('content_id')->nullable()->change();
        $table->unsignedBigInteger('content_id')->nullable()->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      // Change back content_type and content_id to not nullable (assuming they were not nullable before)
      Schema::table('user_video_settings', function (Blueprint $table) {
        $table->string('content_type')->nullable(false)->change();
        $table->unsignedBigInteger('content_id')->nullable(false)->change();
      });
    }
};
