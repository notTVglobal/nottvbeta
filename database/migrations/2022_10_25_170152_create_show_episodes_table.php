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
        Schema::create('show_episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('show_id')->constrained();
            $table->foreignId('image_id')->default(4)->constrained();
            $table->foreignId('show_episode_status_id')->default(1)->constrained()->references('id')->on('show_episode_statuses');
            $table->foreignId('isBeingEditedByUser_id')->nullable()->constrained()->references('id')->on('users');
            $table->string('name');
            $table->longtext('description');
            $table->string('slug');
            $table->string('episode_number')->nullable();
            $table->longtext('notes')->nullable();
            $table->boolean('isPublished')->default(false);
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
        Schema::dropIfExists('show_episodes');
    }
};
