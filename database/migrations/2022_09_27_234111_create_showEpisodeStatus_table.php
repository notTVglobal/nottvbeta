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
        Schema::create('show_episode_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('show_episode_statuses')->insert(
            array(
                'name' => 'Development'
            )
        );
        DB::table('show_episode_statuses')->insert(
            array(
                'name' => 'PreProduction'
            )
        );
        DB::table('show_episode_statuses')->insert(
            array(
                'name' => 'Production'
            )
        );
        DB::table('show_episode_statuses')->insert(
            array(
                'name' => 'Post-Production'
            )
        );
        DB::table('show_episode_statuses')->insert(
            array(
                'name' => 'Review'
            )
        );
        DB::table('show_episode_statuses')->insert(
            array(
                'name' => 'Scheduled'
            )
        );
        DB::table('show_episode_statuses')->insert(
            array(
                'name' => 'Published'
            )
        );
        DB::table('show_episode_statuses')->insert(
            array(
                'name' => 'Archived'
            )
        );
        DB::table('show_episode_statuses')->insert(
            array(
                'name' => 'Frozen'
            )
        );
        DB::table('show_episode_statuses')->insert(
            array(
                'name' => 'Restricted'
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
        Schema::dropIfExists('show_episode_statuses');
    }
};
