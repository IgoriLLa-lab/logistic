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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->foreignId('order_id')->unsigned()->references('id')->on('orders');
            $table->foreignId('product_id')->unsigned()->references('id')->on('products');
            $table->integer('count');
            $table->float('discount')->default(0);
            $table->float('cost');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
