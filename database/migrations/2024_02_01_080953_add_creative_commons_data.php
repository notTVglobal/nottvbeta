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
    DB::table('creative_commons')->insert([
        [
            'name' => 'Legacy Copyright',
            'tag' => '&copy;',
            'description' => 'Traditional copyright gives owners the exclusive right to make copies of their work, usually for a limited time. If people want to use copyrighted work, they usually have to ask for permission from the creator.',
            'link' => 'https://en.wikipedia.org/wiki/Copyright',
        ],
        [
            'name' => 'Public Domain',
            'tag' => '',
            'description' => 'Creative materials that are not protected by intellectual property laws such as copyright, trademark, or patent laws. The public owns these works, not an individual author or artist.',
            'link' => 'https://en.wikipedia.org/wiki/Public_domain',
        ],
    ]);
  }

  public function down()
  {
    // You can add the logic to remove these rows in the "down" method if needed.
  }
};
