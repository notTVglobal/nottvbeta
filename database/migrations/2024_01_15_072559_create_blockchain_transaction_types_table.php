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
        Schema::create('blockchain_transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'team_transfer', 'show_transfer', 'smart_contract'
            $table->timestamps();
        });

        // Insert rows
        DB::table('blockchain_transaction_types')->insert([
            ['name' => 'team_transfer'],
            ['name' => 'show_transfer'],
            ['name' => 'smart_contract'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blockchain_transaction_types');
    }
};
