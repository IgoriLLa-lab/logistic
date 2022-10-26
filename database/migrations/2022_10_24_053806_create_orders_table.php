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
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->unsigned()->autoIncrement();
            $table->string('customer', 255);
            $table->string('phone', 255);
            $table->timestamp('created_at');
            $table->timestamp('completed_at');
            $table->foreignId('user_id')->unsigned()->references('id')->on('users');
            $table->string('type', 255);
            $table->string('status', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
