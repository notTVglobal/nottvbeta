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
    public function up() {
        Schema::create('blockchain_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blockchain_id');
            $table->string('transaction_id'); // Unique identifier for the transaction on the blockchain
            $table->string('sender_address')->nullable(); // Address of the sender (if applicable)
            $table->string('recipient_address')->nullable(); // Address of the recipient (if applicable)
            $table->string('contract_address')->nullable(); // Address of the smart contract (if applicable)
            $table->unsignedBigInteger('status_id'); // E.g., 'pending', 'completed', 'failed'
            $table->text('memo')->nullable(); // Additional memo field
            $table->unsignedBigInteger('type_id'); // Type of the transaction (e.g., 'team_transfer', 'show_transfer', 'smart_contract')
            // Add the polymorphic columns without Laravel's default method
            $table->string('transactionable_type');
            $table->unsignedBigInteger('transactionable_id');

            $table->foreign('status_id')->references('id')->on('blockchain_transaction_statuses');
            $table->foreign('type_id')->references('id')->on('blockchain_transaction_types');
            // Manually specify a shorter index name
            $table->index(['transactionable_type', 'transactionable_id'], 'transactionable_index');


            /** ### Implementation Details
             *
             * // 1. **Polymorphic Relations**:
              * The `morphs` method creates two columns: `transactionable_type` and `transactionable_id`.
              * These columns will store the type and ID of the related model, respectively. For example, if a
              * `BlockchainTransaction` is related to a `Team`, `transactionable_type`
              * would be 'App\Models\Team', and `transactionable_id` would be the ID
              * of the specific team.
              *
             *
             *
             */
            $table->timestamps();

            $table->foreign('blockchain_id')->references('id')->on('blockchains');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blockchain_transactions', function (Blueprint $table) {
            // Drop the foreign key constraints
            $table->dropForeign(['status_id']);
            $table->dropForeign(['type_id']);
            $table->dropForeign(['blockchain_id']);

            // Drop the custom index
            $table->dropIndex('transactionable_index');

            // Drop the polymorphic columns
            $table->dropColumn(['transactionable_type', 'transactionable_id']);

            // Drop other foreign key columns
            $table->dropColumn(['status_id', 'type_id', 'blockchain_id']);
        });

        // Now, you can safely drop the entire table
        Schema::dropIfExists('blockchain_transactions');
    }

};
