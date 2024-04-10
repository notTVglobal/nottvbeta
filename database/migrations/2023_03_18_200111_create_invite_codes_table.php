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
        Schema::create('invite_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained()->references('id')->on('users');
            $table->string('code')->unique();
            $table->boolean('claimed')->default(0);
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
        Schema::dropIfExists('invite_codes');
    }
};
