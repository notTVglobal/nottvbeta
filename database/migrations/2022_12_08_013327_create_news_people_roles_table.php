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
        Schema::create('news_people_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->longText('description')->nullable();
            $table->timestamps();
        });


        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Apprentice'
            )
        );
        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Photographer'
            )
        );
        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Writer'
            )
        );
        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Editor'
            )
        );
        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Reporter'
            )
        );
        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Anchor'
            )
        );
        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Moderator'
            )
        );
        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Assignment Editor'
            )
        );
        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Technical Director'
            )
        );
        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Director'
            )
        );
        DB::table('news_people_roles')->insert(
            array(
                'name' => 'Producer'
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
        Schema::dropIfExists('news_people_roles');
    }
};
