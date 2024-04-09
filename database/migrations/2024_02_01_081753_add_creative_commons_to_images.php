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
      $table->unsignedBigInteger('creative_commons_id')->nullable()->after('user_id');
      $table->foreign('creative_commons_id')->references('id')->on('creative_commons')->onDelete('set null');
    });
  }

  public function down()
  {
    Schema::table('images', function (Blueprint $table) {
      $table->dropForeign(['creative_commons_id']);
      $table->dropColumn('creative_commons_id');
    });
  }
};
