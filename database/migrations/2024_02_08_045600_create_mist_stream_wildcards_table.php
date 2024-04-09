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
        Schema::create('mist_stream_wildcards', function (Blueprint $table) {
          $table->ulid('id')->primary();
          $table->string('mist_stream_id', 36);
          $table->string('name')->comment('The name of the mist stream wildcard.')->unique();
          $table->text('comment')->nullable()->comment('An optional comment for additional context or notes.');
          $table->string('source')->default('push://')->comment('The source of the stream, push:// or file');
          $table->boolean('active')->default(true)->comment('A boolean flag indicating if the content is active or inactive.');
          $table->json('metadata')->nullable()->comment('A JSON column for storing additional metadata about the mist stream wildcard.');

          $table->timestamps();
          // Foreign key constraint
          $table->foreign('mist_stream_id')
              ->references('id')->on('mist_streams')
              ->onDelete('cascade')->comment('Ensures mist stream wildcards are deleted when the corresponding mist stream is deleted.');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mist_stream_wildcards');
    }
};
