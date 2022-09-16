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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->timestamps();
        });
        DB::table('roles')->insert(
            array(
                'role' => 'Standard User'
            )
        );
        DB::table('roles')->insert(
            array(
                'role' => 'Premium Subscriber'
            )
        );
        DB::table('roles')->insert(
            array(
                'role' => 'VIP'
            )
        );
        DB::table('roles')->insert(
            array(
                'role' => 'Creator'
            )
        );
        DB::table('roles')->insert(
            array(
                'role' => 'Administrator'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
