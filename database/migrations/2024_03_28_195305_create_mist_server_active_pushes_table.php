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
        Schema::create('mist_server_active_pushes', function (Blueprint $table) {
          $table->ulid('id')->primary();
          $table->string('push_id')->unique()->index();
          $table->string('stream_name');
          $table->text('original_uri');
          $table->text('processed_uri')->nullable(); // Make nullable if it can be the same as original_uri
          $table->json('logs')->nullable(); // Make nullable if logs are optional
          $table->json('push_status');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mist_server_active_pushes');
    }
};
