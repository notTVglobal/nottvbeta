<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blockchain_transaction_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'pending', 'completed', 'failed'
            $table->timestamps();
        });

        // Insert rows
        DB::table('blockchain_transaction_statuses')->insert([
            ['name' => 'pending'],
            ['name' => 'completed'],
            ['name' => 'failed'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blockchain_transaction_statuses');
    }
};
