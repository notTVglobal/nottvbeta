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
        Schema::create('episodeStatuses', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->timestamps();
        });

        DB::table('episodeStatuses')->insert(
            array(
                'status' => 'Development'
            )
        );
        DB::table('episodeStatuses')->insert(
            array(
                'status' => 'PreProduction'
            )
        );
        DB::table('episodeStatuses')->insert(
            array(
                'status' => 'Production'
            )
        );
        DB::table('episodeStatuses')->insert(
            array(
                'status' => 'Post-Production'
            )
        );
        DB::table('episodeStatuses')->insert(
            array(
                'status' => 'Review'
            )
        );
        DB::table('episodeStatuses')->insert(
            array(
                'status' => 'Scheduled'
            )
        );
        DB::table('episodeStatuses')->insert(
            array(
                'status' => 'Published'
            )
        );
        DB::table('episodeStatuses')->insert(
            array(
                'status' => 'Archived'
            )
        );
        DB::table('episodeStatuses')->insert(
            array(
                'status' => 'Frozen'
            )
        );
        DB::table('episodeStatuses')->insert(
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
        Schema::dropIfExists('episodeStatus');
    }
};
