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
        Schema::create('news_person_role_user', function (Blueprint $table) {
          $table->id();
          $table->foreignId('news_person_id')->constrained('news_people')->onDelete('cascade');
          $table->foreignId('role_id')->constrained('news_people_roles')->onDelete('cascade');
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
        Schema::dropIfExists('news_person_role_user');
    }
};
