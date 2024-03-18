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
      Schema::create('recordings', function (Blueprint $table) {
        $table->ulid('id')->primary();
        $table->string('stream_name')->comment('The name of the recording, corresponding to the filename');
        $table->string('path')->nullable()->comment('The path to the recording');
        $table->string('file_extension')->comment('The file extension of the recording');
        $table->string('mime_type')->nullable()->comment('The MIME type of the recording');
        $table->dateTime('start_time')->comment('The dateTime the recording started');
        $table->dateTime('end_time')->nullable()->comment('The dateTime the recording ended');
        $table->integer('file_size')->nullable()->comment('The file size of the recording');
        $table->integer('duration')->nullable()->comment('The duration of the recording in seconds');
        // Add new columns to store additional recording end details
        $table->bigInteger('milliseconds_first_packet')->nullable()->comment('Timestamp of first media packet in milliseconds');
        $table->bigInteger('milliseconds_last_packet')->nullable()->comment('Timestamp of last media packet in milliseconds');
        $table->string('reason_for_exit')->nullable()->comment('Machine-readable reason for recording end');
        $table->string('human_readable_reason_for_exit')->nullable()->comment('Human-readable reason for recording end');

        $table->string('mist_stream_wildcard_id')->nullable()->comment('The associated MistStreamWildcard ID');
        $table->foreign('mist_stream_wildcard_id')->references('id')->on('mist_stream_wildcards')->onDelete('set null');
        $table->string('model_type')->nullable()->comment('The type of associated model, if any (e.g., show or show_episode)');
        $table->string('model_id')->nullable()->comment('The ID of the associated model, if any');
        $table->string('download_url')->nullable()->comment('The URL for downloading the recording');
        // Ensure other necessary fields are already defined
        $table->timestamps();
        // Adding indexes
        $table->index('model_type', 'recording_model_type_index');
        $table->index('model_id', 'recording_model_id_index');

      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recordings');
    }
};
