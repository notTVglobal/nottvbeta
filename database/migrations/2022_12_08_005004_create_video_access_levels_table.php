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
        Schema::create('video_access_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        DB::table('video_access_levels')->insert(
            array(
                'name' => 'Public Domain'
            )
        );
        DB::table('video_access_levels')->insert(
            array(
                'name' => 'Subscription'
            )
        );
        DB::table('video_access_levels')->insert(
            array(
                'name' => 'PPV'
            )
        );
        DB::table('video_access_levels')->insert(
            array(
                'name' => 'Purchase'
            )
        );
        DB::table('video_access_levels')->insert(
            array(
                'name' => '3rd Party License'
            )
        );
        DB::table('video_access_levels')->insert(
            array(
                'name' => 'Commercial'
            )
        );
        DB::table('video_access_levels')->insert(
            array(
                'name' => 'Non-Commercial'
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
        Schema::dropIfExists('video_access_levels');
    }
};
