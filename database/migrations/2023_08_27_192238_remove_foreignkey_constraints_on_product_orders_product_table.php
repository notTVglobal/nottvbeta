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
        Schema::table('product_order_product', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
        Schema::table('product_order_product', function (Blueprint $table) {
            $table->integer('order_id');
            $table->integer('product_id');
        });
    }
    public function down()
    {
        Schema::table('product_order_product', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('product_id');
        });
        Schema::table('product_order_product', function (Blueprint $table) {
            $table->foreignId('order_id')->references('id')->on('product_orders');
            $table->foreignId('product_id')->references('id')->on('products');

        });
    }
};
