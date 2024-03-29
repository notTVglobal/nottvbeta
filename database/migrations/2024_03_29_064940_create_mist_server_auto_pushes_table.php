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
      Schema::create('mist_server_auto_pushes', function (Blueprint $table) {
        $table->id();
        $table->string('stream_name')->index();
        $table->string('uri')->index();
        $table->text('col_3')->nullable();
        $table->text('col_4')->nullable();
        $table->text('col_5')->nullable();
        $table->text('col_6')->nullable();
        $table->text('col_7')->nullable();
        $table->text('col_8')->nullable();
        $table->text('col_9')->nullable();
        $table->text('col_10')->nullable();
        $table->json('auto_push_entry')->nullable();
        $table->timestamp('scheduletime')->nullable();
        $table->timestamp('completetime')->nullable();
        // Add any other placeholder columns here
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
        Schema::dropIfExists('mist_server_auto_pushes');
    }
};
