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
        Schema::create('team_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->timestamps();
        });

        // Insert default statuses
        DB::table('team_statuses')->insert([
            ['status' => 'new'],
            ['status' => 'active'],
            ['status' => 'going concern'],
            ['status' => 'distressed'],
            ['status' => 'insolvent'],
            ['status' => 'closed'],
            ['status' => 'acquisition'],
            ['status' => 'merger'],
            ['status' => 'frozen'],
            ['status' => 'suspended'],
            ['status' => 'inactive'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_statuses');
    }
};
