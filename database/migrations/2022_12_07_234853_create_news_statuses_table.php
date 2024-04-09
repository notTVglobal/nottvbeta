<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('news_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        DB::table('news_statuses')->insert(
            array(
                'name' => 'New'
            )
        );
        DB::table('news_statuses')->insert(
            array(
                'name' => 'Rejected'
            )
        );
        DB::table('news_statuses')->insert(
            array(
                'name' => 'Accepted'
            )
        );
        DB::table('news_statuses')->insert(
            array(
                'name' => 'Working on'
            )
        );
        DB::table('news_statuses')->insert(
            array(
                'name' => 'Needs Review'
            )
        );
        DB::table('news_statuses')->insert(
            array(
                'name' => 'Published'
            )
        );
        DB::table('news_statuses')->insert(
            array(
                'name' => 'Hidden'
            )
        );
        DB::table('news_statuses')->insert(
            array(
                'name' => 'Creators Only'
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
        Schema::dropIfExists('news_statuses');
    }
};
