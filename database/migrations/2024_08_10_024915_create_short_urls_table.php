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
      Schema::create('short_urls', function (Blueprint $table) {
        $table->id();
        $table->string('identifier')->default('r'); // Default identifier to 'r'
        $table->string('custom_name')->unique(); // Custom name chosen by the user, ensure uniqueness
        $table->string('original_url'); // Original URL that the short URL redirects to
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Foreign key to users table
        $table->foreignId('show_id')->nullable()->constrained()->nullOnDelete(); // Foreign key to shows table
        $table->unsignedInteger('clicks')->default(0); // Track the number of times the short URL has been clicked
        $table->boolean('is_active')->default(true); // To allow deactivation of a URL if necessary
        $table->timestamps(); // To track when the URL was created and last updated
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_urls');
    }
};
