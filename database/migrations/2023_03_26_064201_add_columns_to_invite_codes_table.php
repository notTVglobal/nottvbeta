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
        Schema::table('invite_codes', function (Blueprint $table) {
            $table->string('notes')->nullable()->default(null);
            $table->foreignId('claimed_by')->nullable()->default(null)->constrained()->references('id')->on('users');
            $table->dateTime('claimed_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invite_codes', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->dropForeign(['claimed_by']);
            $table->dropColumn('claimed_by');
            $table->dropColumn('claimed_at');
        });
    }
};
