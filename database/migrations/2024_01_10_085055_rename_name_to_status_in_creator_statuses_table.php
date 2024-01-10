<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNameToStatusInCreatorStatusesTable extends Migration
{
    public function up()
    {
        Schema::table('creator_statuses', function (Blueprint $table) {
            $table->renameColumn('name', 'status');
        });
    }

    public function down()
    {
        Schema::table('creator_statuses', function (Blueprint $table) {
            $table->renameColumn('status', 'name');
        });
    }
};
