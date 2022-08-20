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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained();
            $table->string('user_address_1')->nullable();
            $table->string('user_address_2')->nullable();
            $table->string('user_address_city')->nullable();
            $table->string('user_address_province')->nullable();
            $table->string('user_address_country')->nullable();
            $table->string('user_address_postal_code')->nullable();
            $table->string('user_phone')->nullable();
            $table->string('creator_number')->nullable();
            $table->string('subscription_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(array_merge([
                'role_id',
                'user_address_1',
                'user_address_2',
                'user',
                'user_city',
                'user_province',
                'user_country',
                'user_postal_code',
                'user_phone',
                'creator_number',
                'subscription_status',
            ]));
        });
    }
};
