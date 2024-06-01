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
        Schema::create('news_tips', function (Blueprint $table) {
          $table->id();
          $table->text('name');
          $table->text('email');
          $table->text('postalCode');
          $table->text('message');
          $table->foreignId('news_person_id')->nullable()->constrained('news_people'); // Assuming users table for reporters
          $table->timestamp('read_at')->nullable()->comment('Timestamp when the message was read.');
          $table->string('read_by')->nullable()->comment('The person who read the message.');
          $table->softDeletes()->comment('Timestamp when the message was soft deleted.');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_tips');
    }
};
