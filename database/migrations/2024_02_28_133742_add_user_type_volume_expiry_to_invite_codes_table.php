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
        $table->unsignedBigInteger('user_role_id')->nullable()->after('code');
        $table->foreign('user_role_id')->references('id')->on('roles')->onDelete('set null');
        $table->integer('volume')->default(1)->after('user_role_id'); // Number of times the code can be used
        $table->integer('used_count')->default(0)->after('volume'); // Tracks usage
        $table->dateTime('expiry_date')->nullable()->after('used_count'); // Optional expiry date
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
        $table->dropColumn(['volume', 'used_count', 'expiry_date']);
        $table->dropForeign(['user_role_id']); // Drops foreign key constraint
        $table->dropColumn('user_role_id'); // Drops the column
      });
    }
};
