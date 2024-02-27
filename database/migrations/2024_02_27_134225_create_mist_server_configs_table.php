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
    Schema::create('mist_server_configs', function (Blueprint $table) {
      $table->id();
      $table->text('config');
      $table->timestamps(); // Adds created_at and updated_at columns
    });
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mist_server_configs');
    }
};
