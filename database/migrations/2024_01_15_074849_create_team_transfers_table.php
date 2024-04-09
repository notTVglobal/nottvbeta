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
        Schema::create('team_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('former_owner_user_id');
            $table->unsignedBigInteger('new_owner_user_id');
            $table->unsignedBigInteger('blockchain_id')->nullable();
            $table->unsignedBigInteger('blockchain_transaction_id')->nullable();
            $table->unsignedBigInteger('transfer_status_id');
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('former_owner_user_id')->references('id')->on('users');
            $table->foreign('new_owner_user_id')->references('id')->on('users');
            $table->foreign('blockchain_id')->references('id')->on('blockchains');
            $table->foreign('blockchain_transaction_id')->references('id')->on('blockchain_transactions');
            $table->foreign('transfer_status_id')->references('id')->on('team_transfer_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_transfers', function (Blueprint $table) {
            // Drop the foreign key constraints
            $table->dropForeign(['team_id']);
            $table->dropForeign(['former_owner_user_id']);
            $table->dropForeign(['new_owner_user_id']);
            $table->dropForeign(['blockchain_id']);
            $table->dropForeign(['blockchain_transaction_id']);
            $table->dropForeign(['transfer_status_id']);
        });

        // Now, you can safely drop the entire table
        Schema::dropIfExists('team_transfers');
    }
};
