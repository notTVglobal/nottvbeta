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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('file_name')->unique();
            $table->string('extension')->nullable();
            $table->integer('size')->nullable();
            $table->string('type')->nullable();
            $table->string('full_url')->nullable();
            $table->foreignId('poster')->default(4)->constrained()->references('id')->on('images');
            $table->string('sprite_path')->nullable();
            $table->string('sprite_full_url')->nullable();
            $table->integer('live_count')->nullable();
            $table->boolean('is_live')->nullable();
            $table->string('encryption_key')->nullable();
            $table->string('hash')->nullable();
            $table->foreignId('video_status')->nullable()->constrained()->references('id')->on('video_statuses');
            $table->foreignId('category')->nullable()->constrained()->references('id')->on('video_categories');
            $table->foreignId('commons_license')->nullable()->constrained()->references('id')->on('creative_commons');
            $table->string('nft_address')->nullable();
            $table->foreignId('access_level')->nullable()->constrained()->references('id')->on('video_access_levels');
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
        Schema::dropIfExists('videos');
    }
};
