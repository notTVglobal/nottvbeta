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
        Schema::create('show_episode_status', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->timestamps();
        });

        DB::table('show_episode_status')->insert(
            array(
                'status' => 'Development'
            )
        );
        DB::table('show_episode_status')->insert(
            array(
                'status' => 'PreProduction'
            )
        );
        DB::table('show_episode_status')->insert(
            array(
                'status' => 'Production'
            )
        );
        DB::table('show_episode_status')->insert(
            array(
                'status' => 'Post-Production'
            )
        );
        DB::table('show_episode_status')->insert(
            array(
                'status' => 'Review'
            )
        );
        DB::table('show_episode_status')->insert(
            array(
                'status' => 'Scheduled'
            )
        );
        DB::table('show_episode_status')->insert(
            array(
                'status' => 'Published'
            )
        );
        DB::table('show_episode_status')->insert(
            array(
                'status' => 'Archived'
            )
        );
        DB::table('show_episode_status')->insert(
            array(
                'status' => 'Frozen'
            )
        );
        DB::table('show_episode_status')->insert(
            array(
                'status' => 'Restricted'
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
        Schema::dropIfExists('show_episode_status');
    }
};
