<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansTable extends Migration
{
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('price_id');
            $table->string('product_id');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('image_id')->references('id')->on('images')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscription_plans');
    }
}

