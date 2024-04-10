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
      Schema::create('mist_stream_push_destinations', function (Blueprint $table) {
        $table->ulid('id')->primary();
        $table->ulid('mist_stream_wildcard_id');
        $table->string('rtmp_url');
        $table->string('rtmp_key')->nullable();
        $table->text('comment')->nullable();
        $table->boolean('has_auto_push')->default(0);
        $table->boolean('push_is_started')->default(0);
        $table->string('destination_name')->nullable();
        $table->string('destination_image')->nullable();
        $table->timestamps();

        $table->foreign('mist_stream_wildcard_id')->references('id')->on('mist_stream_wildcards')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mist_stream_push_destinations');
    }
};
