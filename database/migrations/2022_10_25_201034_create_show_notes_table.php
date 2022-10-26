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
            $table->foreignId('show_id')->default(null)->constrained();
            $table->foreignId('show_episode_id')->default(null)->constrained();
            $table->foreignId('user_id')->default(null)->constrained();
            $table->string('title');
            $table->text('content');
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
        Schema::dropIfExists('show_notes');
    }
};
