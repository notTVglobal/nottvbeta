<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('creator_messages', function (Blueprint $table) {
          $table->id();
          $table->foreignId('sender_id')->nullable()->comment('References id from users table. Nullable because sender might not be a registered user.')->constrained('users')->onDelete('cascade');
          $table->foreignId('recipient_id')->comment('References id from creators table.')->constrained('creators')->onDelete('cascade');
          $table->string('subject')->default('no subject')->comment('The subject of the message.');
          $table->text('message')->comment('The content of the message.');
          $table->timestamp('read_at')->nullable()->comment('Timestamp when the message was read.');
          $table->softDeletes()->comment('Timestamp when the message was soft deleted.');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creator_messages');
    }
};
