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
        Schema::create('show_notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('show_id')->default(null)->constrained();
            $table->foreignId('episode_id')->default(null)->constrained();
            $table->foreignId('user_id')->default(null)->constrained();
            $table->string('title');
            $table->text('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('show_notes');
    }
};
