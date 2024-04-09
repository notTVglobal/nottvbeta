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
        Schema::create('show_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('show_statuses')->insert(
            array(
                'name' => 'New'
            )
        );
        DB::table('show_statuses')->insert(
            array(
                'name' => 'Active'
            )
        );
        DB::table('show_statuses')->insert(
            array(
                'name' => 'On Hold'
            )
        );
        DB::table('show_statuses')->insert(
            array(
                'name' => 'Inactive'
            )
        );
        DB::table('show_statuses')->insert(
            array(
                'name' => 'Archived'
            )
        );
        DB::table('show_statuses')->insert(
            array(
                'name' => 'Frozen'
            )
        );
        DB::table('show_statuses')->insert(
            array(
                'name' => 'Restricted'
            )
        );
        DB::table('show_statuses')->insert(
            array(
                'name' => 'Hidden'
            )
        );
        DB::table('show_statuses')->insert(
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
        Schema::dropIfExists('show_statuses');
    }
};
