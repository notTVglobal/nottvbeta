<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCreatorStatusTable extends Migration
{
    public function up()
    {
        Schema::rename('creator_status', 'creator_statuses');
    }

    public function down()
    {
        Schema::rename('creator_statuses', 'creator_status');
    }
};
