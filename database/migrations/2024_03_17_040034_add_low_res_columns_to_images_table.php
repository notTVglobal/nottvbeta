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
      Schema::table('images', function (Blueprint $table) {
        // Ensure 'folder' column exists before adding new columns after it
        if (Schema::hasColumn('images', 'folder')) {
          $table->string('low_res_image_folder')->nullable()->after('folder');
          $table->string('low_res_blur_folder')->nullable()->after('low_res_image_folder');
        }
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('images', function (Blueprint $table) {
        $table->dropColumn(['low_res_image_folder', 'low_res_blur_folder']);
      });
    }
};
