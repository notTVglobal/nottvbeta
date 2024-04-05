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
      Schema::table('show_schedule_recurrence_details', function (Blueprint $table) {
        $table->boolean('monday')->after('days_of_week')->index()->default(false);
        $table->boolean('tuesday')->after('monday')->index()->default(false);
        $table->boolean('wednesday')->after('tuesday')->index()->default(false);
        $table->boolean('thursday')->after('wednesday')->index()->default(false);
        $table->boolean('friday')->after('thursday')->index()->default(false);
        $table->boolean('saturday')->after('friday')->index()->default(false);
        $table->boolean('sunday')->after('saturday')->index()->default(false);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_schedule_recurrence_details', function (Blueprint $table) {
          $table->dropColumn(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
        });
    }
};
