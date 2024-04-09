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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        DB::table('channels')->insert(
            array(
                'name' => 'Stream'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Live'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Ambient'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Local'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'News'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Talk'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Music'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Sports'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Variety'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Kids'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Documentaries'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Education'
            )
        );
        DB::table('channels')->insert(
            array(
                'name' => 'Random'
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
        Schema::dropIfExists('channels');
    }
};
